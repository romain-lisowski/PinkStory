<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntity implements IdentifiableInterface, ActivatableInterface, TimestampableInterface
{
    use IdentifiableTrait;
    use ActivatableTrait;
    use TimestampableTrait;

    public function __construct()
    {
        // init zero values
        $this->id = '';
        $this->activated = true;
        $this->createdAt = new DateTime();
        $this->lastUpdatedAt = new DateTime();

        // init values
        $this->generateId()
            ->updateLastUpdatedAt()
        ;
    }
}
