<?php

declare(strict_types=1);

namespace App\Common\Domain\File;

trait ImageableTrait
{
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

    abstract public function getImageBasePath(): string;
}
