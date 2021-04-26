<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Language\Query\Model\Language;

final class UserForUpdate extends UserMedium
{
    private string $email;

    public function __construct(string $id, string $gender, string $name, string $email, bool $imageDefined, Language $language)
    {
        parent::__construct($id, $gender, $name, $imageDefined, $language);

        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
