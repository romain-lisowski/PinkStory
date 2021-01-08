<?php

declare(strict_types=1);

namespace App\Query;

use Symfony\Component\Validator\Constraints as Assert;

trait PaginableTrait
{
    /**
     * @Assert\NotBlank
     */
    public int $limit = PaginableInterface::LIMIT;

    /**
     * @Assert\NotBlank
     */
    public int $offset = PaginableInterface::OFFSET;
}
