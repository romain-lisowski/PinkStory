<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Common\Domain\File\ImageableInterface;
use App\Common\Domain\File\ImageableTrait;
use App\Language\Query\Model\Language;
use App\User\Domain\Model\User as DomainUser;
use Symfony\Component\Serializer\Annotation as Serializer;

class UserMedium extends User implements ImageableInterface
{
    use ImageableTrait;

    private string $gender;
    private string $genderReading;
    private string $name;
    private string $nameSlug;
    private \DateTime $createdAt;
    private Language $language;

    /**
     * @Serializer\Ignore()
     */
    private bool $imageDefined;

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGenderReading(): string
    {
        return $this->genderReading;
    }

    public function setGenderReading(string $genderReading): self
    {
        $this->genderReading = $genderReading;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function setNameSlug(string $nameSlug): self
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    public function isImageDefined(): bool
    {
        return $this->imageDefined;
    }

    public function setImageDefined(bool $imageDefined): self
    {
        $this->imageDefined = $imageDefined;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @Serializer\Ignore()
     */
    public function hasImage(): bool
    {
        return $this->isImageDefined();
    }

    /**
     * @Serializer\Ignore()
     */
    public static function getImageBasePath(): string
    {
        return DomainUser::getImageBasePath();
    }
}
