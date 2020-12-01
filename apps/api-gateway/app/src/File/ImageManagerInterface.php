<?php

declare(strict_types=1);

namespace App\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageManagerInterface
{
    public function upload(UploadedFile $file, ImageableInterface $image): bool;

    public function remove(ImageableInterface $image): bool;
}
