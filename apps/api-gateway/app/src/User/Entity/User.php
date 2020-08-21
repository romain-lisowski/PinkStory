<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Entity\ActivatedTrait;
use App\Entity\IdentifierTrait;
use App\Entity\TimestampTrait;
use App\User\Validator\Constraints as AppUserAssert;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="usr_user")
 * @ORM\Entity(repositoryClass="App\User\Repository\UserRepository")
 * @UniqueEntity(
 *      fields = {"email"}
 * )
 */
final class User implements UserInterface
{
    use IdentifierTrait;
    use ActivatedTrait;
    use TimestampTrait;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="name", type="string", length=255)
     */
    private string $name;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="name_slug", type="string", length=255)
     */
    private string $nameSlug;

    /**
     * @Assert\NotBlank
     * @AppUserAssert\Email
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @Assert\NotNull
     * @ORM\Column(name="email_validated", type="boolean")
     */
    private bool $emailValidated;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="email_validation_secret", type="guid", unique=true)
     */
    private string $emailValidationSecret;

    /**
     * @Assert\NotNull
     * @ORM\Column(name="email_validation_secret_used", type="boolean")
     */
    private bool $emailValidationSecretUsed;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="password", type="string", length=255)
     */
    private string $password;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="password_forgotten_secret", type="guid", unique=true)
     */
    private string $passwordForgottenSecret;

    /**
     * @Assert\NotNull
     * @ORM\Column(name="password_forgotten_secret_used", type="boolean")
     */
    private bool $passwordForgottenSecretUsed;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="password_forgotten_secret_created_at", type="datetime")
     */
    private DateTime $passwordForgottenSecretCreatedAt;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="secret", type="guid")
     */
    private string $secret;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="role", type="string", length=255)
     */
    private string $role;

    public function __construct()
    {
        $this->generateId()
            ->setActivated(true)
            ->initCreatedAt()
            ->updateLastUpdatedAt()
            ->regenerateEmailValidationSecret()
            ->regeneratePasswordForgottenSecret()
            ->setPasswordForgottenSecretUsed(true) // not claimed by user at account creation, so block it until new claim
            ->regenerateSecret()
            ->setRole(UserRole::ROLE_USER)
        ;
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

    public function rename(string $name): self
    {
        $this->setName($name);

        $slugger = new AsciiSlugger();
        $this->setNameSlug($slugger->slug($name)->lower()->toString());

        return $this;
    }

    public function setNameSlug(string $slug): self
    {
        $this->nameSlug = $slug;

        return $this;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function changeEmail(string $email): self
    {
        $this->setEmail((new UnicodeString($email))->lower()->toString());
        $this->regenerateEmailValidationSecret();

        return $this;
    }

    public function isEmailValidated(): bool
    {
        return $this->emailValidated;
    }

    public function setEmailValidated(bool $validated): self
    {
        $this->emailValidated = $validated;

        return $this;
    }

    public function validateEmail(): self
    {
        $this->setEmailValidated(true);
        $this->setEmailValidationSecretUsed(true);

        return $this;
    }

    public function getEmailValidationSecret(): string
    {
        return $this->emailValidationSecret;
    }

    public function setEmailValidationSecret(string $secret): self
    {
        $this->emailValidationSecret = $secret;

        return $this;
    }

    public function regenerateEmailValidationSecret(): self
    {
        $this->setEmailValidationSecret(Uuid::v4()->toRfc4122());
        $this->setEmailValidationSecretUsed(false);
        $this->setEmailValidated(false);

        return $this;
    }

    public function isEmailValidationSecretUsed(): bool
    {
        return $this->emailValidationSecretUsed;
    }

    public function setEmailValidationSecretUsed(bool $used): self
    {
        $this->emailValidationSecretUsed = $used;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function changePassword(string $password): self
    {
        $this->setPassword($password);
        $this->setPasswordForgottenSecretUsed(true);

        return $this;
    }

    public function getPasswordForgottenSecret(): string
    {
        return $this->passwordForgottenSecret;
    }

    public function setPasswordForgottenSecret(string $secret): self
    {
        $this->passwordForgottenSecret = $secret;

        return $this;
    }

    public function regeneratePasswordForgottenSecret(): self
    {
        $this->setPasswordForgottenSecret(Uuid::v4()->toRfc4122());
        $this->setPasswordForgottenSecretUsed(false);
        $this->initPasswordForgottenSecretCreatedAt();

        return $this;
    }

    public function isPasswordForgottenSecretUsed(): bool
    {
        return $this->passwordForgottenSecretUsed;
    }

    public function setPasswordForgottenSecretUsed(bool $used): self
    {
        $this->passwordForgottenSecretUsed = $used;

        return $this;
    }

    public function getPasswordForgottenSecretCreatedAt(): DateTime
    {
        return $this->passwordForgottenSecretCreatedAt;
    }

    public function setPasswordForgottenSecretCreatedAt(DateTime $date): self
    {
        $this->passwordForgottenSecretCreatedAt = $date;

        return $this;
    }

    public function initPasswordForgottenSecretCreatedAt(): self
    {
        $this->setPasswordForgottenSecretCreatedAt(new DateTime());

        return $this;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function regenerateSecret(): self
    {
        $this->setSecret(Uuid::v4()->toRfc4122());

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getSalt(): ?string
    {
        // not needed with the current algorithm in security.yaml
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
