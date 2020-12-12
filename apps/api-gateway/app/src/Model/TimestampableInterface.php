<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;

interface TimestampableInterface
{
    public function getCreatedAt(): DateTime;

    public function getLastUpdatedAt(): DateTime;
}
