<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

interface ImageableInterface
{
    public function hasImage(): bool;

    public function getImageName(bool $forced = false): ?string;

    public function getImagePath(bool $forced = false): ?string;

    public static function getImageBasePath(): string;

    public function getImageUrl(): ?string;

    public function setImageUrl(string $baseUrl): self;
}
