<?php

declare(strict_types=1);

namespace App\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface
{
    public function upload(UploadedFile $file): bool;

    public function remove(): void;
}
