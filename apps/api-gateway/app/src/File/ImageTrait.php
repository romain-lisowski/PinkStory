<?php

declare(strict_types=1);

namespace App\File;

use Symfony\Component\Serializer\Annotation\Groups;

trait ImageTrait
{
    /**
     * @Groups({"medium", "full"})
     */
    public function getImagePath(): ?string
    {
        return $this->getId().'.jpeg';
    }
}
