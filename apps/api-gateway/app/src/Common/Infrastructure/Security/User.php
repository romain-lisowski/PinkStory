<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Security;

use Symfony\Component\Validator\Constraints\Uuid;

final class User
{
    private Uuid $id;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
