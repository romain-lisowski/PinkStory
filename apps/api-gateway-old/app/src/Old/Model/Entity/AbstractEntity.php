<?php

declare(strict_types=1);

namespace App\Model\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntity implements EntityInterface, IdentifiableInterface, TimestampableInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    public function __construct()
    {
        // init zero values
        $this->id = '';
        $this->createdAt = new DateTime();
        $this->lastUpdatedAt = new DateTime();

        // init values
        $this->generateId()
            ->updateLastUpdatedAt()
        ;
    }
}
