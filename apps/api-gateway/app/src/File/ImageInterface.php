<?php

declare(strict_types=1);

namespace App\File;

interface ImageInterface
{
    public function hasImage(): bool;

    public function getImageName(bool $forced = false): ?string;

    public function getImagePath(bool $forced = false): ?string;

    public function getImageBasePath(): string;
}
