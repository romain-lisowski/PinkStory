<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Handler\HandlerableInterface;
use App\Handler\HandlerableTrait;
use App\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class StoryGetQuery implements QueryInterface, HandlerableInterface
{
    use HandlerableTrait;

    /**
     * @Assert\NotBlank
     */
    public string $id = '';

    /**
     * @Assert\NotBlank
     */
    public string $languageId = '';
}
