<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use App\Common\Query\Model\AbstractModel;

class StoryTheme extends AbstractModel
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
