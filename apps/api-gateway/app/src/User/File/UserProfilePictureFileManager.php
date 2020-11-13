<?php

declare(strict_types=1);

namespace App\User\File;

use App\User\Entity\User;
use Imagine\Image\Box;
use Imagine\Imagick\Imagine;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

final class UserProfilePictureFileManager implements UserProfilePictureFileManagerInterface
{
    private ParameterBagInterface $params;
    private User $user;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function upload(UploadedFile $file): bool
    {
        try {
            // open image
            $imagine = new Imagine();
            $image = $imagine->open($file->getRealPath());

            // get uploaded image informations
            $uploadedImageExtension = $file->guessExtension();
            $uploadedImageWidth = $image->getSize()->getWidth();
            $uploadedImageHeight = $image->getSize()->getHeight();

            // resize image
            $uploadedImageRatio = $uploadedImageWidth >= $uploadedImageHeight ? ($uploadedImageWidth / $uploadedImageHeight) : ($uploadedImageHeight / $uploadedImageWidth);
            $imageWidth = $uploadedImageWidth >= $uploadedImageHeight ? 1500 : (1500 / $uploadedImageRatio);
            $imageHeight = $uploadedImageHeight >= $uploadedImageWidth ? 1500 : (1500 / $uploadedImageRatio);
            $image->resize(new Box($imageWidth, $imageHeight));

            // save image
            $image->save($this->params->get('project_file_manager_dir').$this->params->get('project_file_manager_image_dir').$this->params->get('project_file_manager_image_user_dir').'/'.$this->user->getId().'.jpeg', ['jpeg_quality' => 80]);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function remove(): void
    {
        $file = new File($this->params->get('project_file_manager_dir').$this->params->get('project_file_manager_image_dir').$this->params->get('project_file_manager_image_user_dir').'/'.$this->user->getProfilePicturePath());

        $filesystem = new Filesystem();
        $filesystem->remove($file->getRealPath());
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
