<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

use Symfony\Component\Serializer\Annotation as Serializer;

trait ImageableTrait
{
    private ?string $imageUrl = null;

    /**
     * @Serializer\Ignore()
     */
    public function hasImage(): bool
    {
        return true;
    }

    /**
     * @Serializer\Ignore()
     */
    public function getImageName(bool $forced = false): ?string
    {
        if (false === $this->hasImage() && false === $forced) {
            return null;
        }

        return $this->getId().'.jpeg';
    }

    /**
     * @Serializer\Ignore()
     */
    public function getImagePath(bool $forced = false): ?string
    {
        if (false === $this->hasImage() && false === $forced) {
            return null;
        }

        return '/'.static::getImageBasePath().'/'.$this->getImageName($forced);
    }

    abstract public static function getImageBasePath(): string;

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $baseUrl): self
    {
        if (true === $this->hasImage()) {
            $this->imageUrl = $baseUrl.$this->getImagePath();
        }

        return $this;
    }
}
