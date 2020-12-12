<?php

declare(strict_types=1);

namespace App\File;

use App\File\Exception\ImageDeleteException;
use App\File\Exception\ImageUploadException;
use App\File\Model\ImageableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageManagerInterface
{
    /**
     * Upload image.
     *
     * @throws ImageUploadException
     */
    public function upload(UploadedFile $file, ImageableInterface $image): void;

    /**
     * Delete image.
     *
     * @throws ImageDeleteException
     */
    public function delete(ImageableInterface $image): void;
}
