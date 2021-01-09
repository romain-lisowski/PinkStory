<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="usr_user")
 * @UniqueEntity(
 *      fields = {"email"}
 * )
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid", unique=true)
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    private string $id;

    /**
     * @ORM\Column(name="gender", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserGender", "getChoices"})
     */
    private string $gender;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @ORM\Column(name="name_slug", type="string", length=255)
     * @Assert\NotBlank
     */
    private string $nameSlug;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    private string $email;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank
     */
    private string $password;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="last_updated_at", type="datetime")
     * @Assert\NotBlank
     */
    private DateTime $lastUpdatedAt;

    public function __construct()
    {
        // init values
        $this->generateId()
            ->setCreatedAt(new DateTime())
            ->updateLastUpdatedAt()
        ;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function generateId(): self
    {
        $this->setId(Uuid::v4()->toRfc4122());

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

    public function updateGender(string $gender): self
    {
        $this->setGender($gender);

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        $slugger = new AsciiSlugger();
        $this->nameSlug = $slugger->slug($name)->lower()->toString();

        return $this;
    }

    public function rename(string $name): self
    {
        $this->setName($name);

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
        $this->email = (new UnicodeString($email))->lower()->toString();

        return $this;
    }

    public function updateEmail(string $email): self
    {
        $this->setEmail($email);

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

    public function updatePassword(string $password): self
    {
        $this->setPassword($password);

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $date): self
    {
        $this->createdAt = $date;

        return $this;
    }

    public function getLastUpdatedAt(): DateTime
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(DateTime $date): self
    {
        $this->lastUpdatedAt = $date;

        return $this;
    }

    public function updateLastUpdatedAt(): self
    {
        $this->setLastUpdatedAt(new DateTime());

        return $this;
    }
}
