<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Common\Domain\Event\EventInterface;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Symfony\Component\Validator\Constraints as Assert;

final class UserCreatedEvent implements EventInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
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
     * @AppUserAssert\Email
     */
    private string $email;

    /**
     * @Assert\NotBlank
     */
    private string $emailValidationCode;

    /**
     * @Assert\NotBlank
     */
    private string $password;

    private ?string $imagePath;

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserRole", "getChoices"})
     */
    private string $role;

    /**
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserStatus", "getChoices"})
     */
    private string $status;

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $languageId;

    /**
     * @Assert\NotBlank
     */
    private array $readingLanguageIds;

    public function __construct(string $id, string $gender, string $name, string $email, string $emailValidationCode, string $password, ?string $imagePath, string $role, string $status, string $languageId, array $readingLanguageIds)
    {
        $this->id = $id;
        $this->gender = $gender;
        $this->name = $name;
        $this->email = $email;
        $this->emailValidationCode = $emailValidationCode;
        $this->password = $password;
        $this->imagePath = $imagePath;
        $this->role = $role;
        $this->status = $status;
        $this->languageId = $languageId;
        $this->readingLanguageIds = $readingLanguageIds;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getEmailValidationCode(): string
    {
        return $this->emailValidationCode;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function getReadingLanguageIds(): array
    {
        return $this->readingLanguageIds;
    }
}
