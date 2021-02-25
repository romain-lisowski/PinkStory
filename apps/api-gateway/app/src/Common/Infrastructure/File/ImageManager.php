<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\File;

use App\Common\Domain\File\ImageableInterface;
use App\Common\Domain\File\ImageDeleteException;
use App\Common\Domain\File\ImageManagerInterface;
use App\Common\Domain\File\ImageUploadException;
use Imagine\Image\Box;
use Imagine\Imagick\Imagine;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;

final class ImageManager implements ImageManagerInterface
{
    private FilesystemOperator $imageStorage;

    public function __construct(FilesystemOperator $imageStorage)
    {
        $this->imageStorage = $imageStorage;
    }

    public function upload(File $image, ImageableInterface $imageable): void
    {
        try {
            // init imagine
            $imagine = new Imagine();
            $tmpImage = $imagine->open($image->getRealPath());

            // get uploaded image informations
            $tmpImageWidth = $tmpImage->getSize()->getWidth();
            $tmpImageHeight = $tmpImage->getSize()->getHeight();

            // resize image
            $tmpImageRatio = $tmpImageWidth >= $tmpImageHeight ? ($tmpImageWidth / $tmpImageHeight) : ($tmpImageHeight / $tmpImageWidth);
            $finalImageWidth = $tmpImageWidth >= $tmpImageHeight ? 1500 : (1500 / $tmpImageRatio);
            $finalImageHeight = $tmpImageHeight >= $tmpImageWidth ? 1500 : (1500 / $tmpImageRatio);
            $tmpImage->resize(new Box($finalImageWidth, $finalImageHeight));

            // save tmp image
            $tmpImagePath = sys_get_temp_dir().'/'.$imageable->getImageName(true);
            $tmpImage->save($tmpImagePath, ['jpeg_quality' => 80]);

            // move to final storage
            $this->imageStorage->write($imageable->getImagePath(true), (new File($tmpImagePath))->getContent());
        } catch (\Throwable $e) {
            throw new ImageUploadException($e);
        }
    }

    public function delete(ImageableInterface $imageable): void
    {
        try {
            $this->imageStorage->delete($imageable->getImagePath(true));
        } catch (\Throwable $e) {
            throw new ImageDeleteException($e);
        }
    }
}
