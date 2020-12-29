<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\UserGender;
use DateTime;
use Symfony\Component\Serializer\Annotation as Serializer;

final class UserForUpdate extends UserMedium
{
    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $email;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', string $gender = UserGender::UNDEFINED, string $email = '', DateTime $createdAt, Language $language)
    {
        parent::__construct($id, $imageDefined, $name, $nameSlug, $gender, $createdAt, $language);

        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
