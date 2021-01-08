<?php

declare(strict_types=1);

namespace App\Model\Dto;

use App\Model\TimestampableTrait as ModelTimestampableTrait;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

trait TimestampableTrait
{
    use ModelTimestampableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private DateTime $createdAt;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private DateTime $lastUpdatedAt;
}
