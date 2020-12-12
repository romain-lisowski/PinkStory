<?php

declare(strict_types=1);

namespace App\File;

use App\File\Exception\ImageDeleteException;
use App\File\Exception\ImageUploadException;
use App\File\Model\ImageableInterface;
use Imagine\Image\Box;
use Imagine\Imagick\Imagine;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

final class ImageManager implements ImageManagerInterface
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function upload(UploadedFile $file, ImageableInterface $image): void
    {
        try {
            // open image
            $imagine = new Imagine();
            $imageFile = $imagine->open($file->getRealPath());

            // get uploaded image informations
            $uploadedImageWidth = $imageFile->getSize()->getWidth();
            $uploadedImageHeight = $imageFile->getSize()->getHeight();

            // resize image
            $uploadedImageRatio = $uploadedImageWidth >= $uploadedImageHeight ? ($uploadedImageWidth / $uploadedImageHeight) : ($uploadedImageHeight / $uploadedImageWidth);
            $imageWidth = $uploadedImageWidth >= $uploadedImageHeight ? 1500 : (1500 / $uploadedImageRatio);
            $imageHeight = $uploadedImageHeight >= $uploadedImageWidth ? 1500 : (1500 / $uploadedImageRatio);
            $imageFile->resize(new Box($imageWidth, $imageHeight));

            // save image
            $imageFile->save($this->params->get('project_file_manager_path').$this->params->get('project_file_manager_image_path').$image->getImagePath(true), ['jpeg_quality' => 80]);
        } catch (Throwable $e) {
            throw new ImageUploadException();
        }
    }

    public function delete(ImageableInterface $image): void
    {
        try {
            $file = new File($this->params->get('project_file_manager_path').$this->params->get('project_file_manager_image_path').$image->getImagePath(true));

            $filesystem = new Filesystem();
            $filesystem->remove($file->getRealPath());
        } catch (Throwable $e) {
            throw new ImageDeleteException();
        }
    }
}
