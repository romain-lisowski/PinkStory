<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\User\Infrastructure\Doctrine\Repository\UserDoctrineORMRepository")
 * @ORM\Table(name="usr_user")
 * @UniqueEntity(
 *      fields = {"email"}
 * )
 */
class User extends AbstractEntity
{
    /**
     * @ORM\Column(name="gender", type="string")
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserGender", "getChoices"})
     */
    private string $gender;

    /**
     * @ORM\Column(name="name", type="string")
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @ORM\Column(name="name_slug", type="string")
     * @Assert\NotBlank
     */
    private string $nameSlug;

    /**
     * @ORM\Column(name="email", type="string", unique=true)
     * @Assert\NotBlank
     * @AppUserAssert\Email
     */
    private string $email;

    /**
     * @ORM\Column(name="password", type="string")
     * @Assert\NotBlank
     */
    private string $password;

    /**
     * @ORM\Column(name="role", type="string")
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserRole", "getChoices"})
     */
    private string $role;

    /**
     * @ORM\Column(name="status", type="string")
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\User\Domain\Model\UserStatus", "getChoices"})
     */
    private string $status;

    public function getGender(): string
    {
        return $this->gender;
    }

    public function updateGender(string $gender): self
    {
        $this->setGender($gender);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $name): self
    {
        $this->setName($name);
        $this->updateLastUpdatedAt();

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

    public function updateEmail(string $email): self
    {
        $this->setEmail($email);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function updatePassword(string $plainPassword, UserPasswordEncoderInterface $passwordEncoder): self
    {
        $this->setPassword($plainPassword, $passwordEncoder);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function updateRole(string $role): self
    {
        $this->setRole($role);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function updateStatus(string $status): self
    {
        $this->setStatus($status);
        $this->updateLastUpdatedAt();

        return $this;
    }

    private function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    private function setName(string $name): self
    {
        $this->name = $name;

        $slugger = new AsciiSlugger();
        $this->nameSlug = $slugger->slug($name)->lower()->toString();

        return $this;
    }

    private function setEmail(string $email): self
    {
        $this->email = (new UnicodeString($email))->lower()->toString();

        return $this;
    }

    private function setPassword(string $plainPassword, UserPasswordEncoderInterface $passwordEncoder): self
    {
        $this->password = $passwordEncoder->encodePassword($plainPassword);

        return $this;
    }

    private function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    private function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
