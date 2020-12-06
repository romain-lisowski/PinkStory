<?php

declare(strict_types=1);

namespace App\File;

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
     * Remove image.
     *
     * @throws ImageRemoveException
     */
    public function remove(ImageableInterface $image): void;
}
