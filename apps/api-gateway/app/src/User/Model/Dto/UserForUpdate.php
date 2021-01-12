<?php

declare(strict_types=1);

namespace App\User\Model\Dto;

use App\Language\Model\Dto\Language;
use App\User\Model\UserGender;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation as Serializer;

final class UserForUpdate extends UserMedium implements UserReadingLanguageableInterface
{
    use UserReadingLanguageableTrait;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private string $email;

    /**
     * @Serializer\Groups({"serializer"})
     */
    private Collection $readingLanguages;

    public function __construct(string $id = '', bool $imageDefined = false, string $name = '', string $nameSlug = '', string $gender = UserGender::UNDEFINED, string $email = '', DateTime $createdAt, Language $language)
    {
        parent::__construct($id, $imageDefined, $name, $nameSlug, $gender, $createdAt, $language);

        $this->email = $email;
        $this->readingLanguages = new ArrayCollection();
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
