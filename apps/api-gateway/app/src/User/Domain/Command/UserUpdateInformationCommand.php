<?php

declare(strict_types=1);

namespace App\User\Domain\Command;

use App\Common\Domain\Command\CommandInterface;
use App\Common\Infrastructure\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserUpdateInformationCommand implements CommandInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\User\Domain\Model\User",
     *      message = "user.validator.constraint.user_not_found"
     * )
     */
    private string $id;

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserGender", "getChoices"})
     */
    private string $gender;

    /**
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     * @AppAssert\Entity(
     *      entityClass = "App\Language\Domain\Model\Language",
     *      message = "language.validator.constraint.language_not_found"
     * )
     */
    private string $languageId;

    /**
     * @Assert\NotBlank,
     * @AppAssert\Entity(
     *      entityClass = "App\Language\Domain\Model\Language",
     *      message = "language.validator.constraint.language_not_found"
     * )
     */
    private array $readingLanguageIds;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function setLanguageId(string $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
    }

    public function getReadingLanguageIds(): array
    {
        return $this->readingLanguageIds;
    }

    public function setReadingLanguageIds(array $readingLanguageIds): self
    {
        $this->readingLanguageIds = $readingLanguageIds;

        return $this;
    }
}
