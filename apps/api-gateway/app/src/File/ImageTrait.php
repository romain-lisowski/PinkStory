<?php

declare(strict_types=1);

namespace App\File;

use Symfony\Component\Serializer\Annotation\Groups;

trait ImageTrait
{
    public function getImageName(bool $forced = false): ?string
    {
        if (false === $this->hasImage() && false === $forced) {
            return null;
        }

        return $this->getId().'.jpeg';
    }

    /**
     * @Groups({"medium", "full"})
     */
    public function getImagePath(bool $forced = false): ?string
    {
        if (false === $this->hasImage() && false === $forced) {
            return null;
        }

        return $this->getImageBasePath().'/'.$this->getImageName($forced);
    }
}
