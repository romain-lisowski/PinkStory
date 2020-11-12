<?php

declare(strict_types=1);

namespace App\User\File;

use App\User\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            $file->move(
                $this->params->get('project_file_manager_dir').$this->params->get('project_file_manager_image_dir').$this->params->get('project_file_manager_image_user_dir'),
                $this->user->getId().'.'.$file->getClientOriginalExtension()
            );

            return true;
        } catch (FileException $e) {
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
