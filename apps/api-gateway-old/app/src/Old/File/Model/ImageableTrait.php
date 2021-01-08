<?php

declare(strict_types=1);

namespace App\File\Model;

trait ImageableTrait
{
    private ?string $imageUrl = null;

    public function hasImage(): bool
    {
        return true;
    }

    public function getImageName(bool $forced = false): ?string
    {
        if (false === $this->hasImage() && false === $forced) {
            return null;
        }

        return $this->getId().'.jpeg';
    }

    public function getImagePath(bool $forced = false): ?string
    {
        if (false === $this->hasImage() && false === $forced) {
            return null;
        }

        return '/'.$this->getImageBasePath().'/'.$this->getImageName($forced);
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $baseUrl = '', bool $forced = false): self
    {
        if (true === $this->hasImage() || true === $forced) {
            $this->imageUrl = $baseUrl.$this->getImagePath($forced);
        }

        return $this;
    }

    abstract public function getImageBasePath(): string;
}
