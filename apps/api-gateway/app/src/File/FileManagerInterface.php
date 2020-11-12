<?php

declare(strict_types=1);

namespace App\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManagerInterface
{
    public function upload(UploadedFile $file): bool;

    public function remove(): void;
}
