<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

use Symfony\Component\HttpFoundation\File\File;

interface ImageManagerInterface
{
    /**
     * @throws ImageUploadException
     */
    public function upload(File $image, ImageableInterface $imageable): void;
}
