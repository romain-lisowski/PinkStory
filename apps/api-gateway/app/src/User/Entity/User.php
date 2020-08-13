<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\Entity\ActivatedTrait;
use App\Entity\IdentifierTrait;
use App\Entity\TimestampTrait;
use App\User\Validator\Constraints as AppUserAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\ByteString;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="usr_user")
 * @ORM\Entity(repositoryClass="App\User\Doctrine\Repository\UserRepository")
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
     * @AppUserAssert\Email
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="password", type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(name="secret", type="string", length=255)
     */
    private string $secret;

    /**
     * @ORM\Column(name="role", type="string", length=255)
     */
    private string $role;

    public function __construct()
    {
        $this->generateUuid()
            ->setActivated(true)
            ->initCreatedAt()
            ->updateLastUpdatedAt()
            ->renewSecret()
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = (new UnicodeString($email))->lower()->toString();

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

    public function getSecret()
    {
        return $this->secret;
    }

    public function renewSecret()
    {
        $this->secret = ByteString::fromRandom(32)->toString();

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
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
