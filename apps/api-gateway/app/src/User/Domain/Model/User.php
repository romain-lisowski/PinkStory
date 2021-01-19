<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Domain\File\ImageableInterface;
use App\Common\Domain\File\ImageableTrait;
use App\Common\Domain\Model\AbstractEntity;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use App\User\Infrastructure\Validator\Constraint as AppUserAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
class User extends AbstractEntity implements ImageableInterface
{
    use ImageableTrait;

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
     * @Assert\NotNull
     * @ORM\Column(name="image_defined", type="boolean")
     */
    private bool $imageDefined;

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

    /**
     * @ORM\OneToMany(targetEntity="App\User\Domain\Model\AccessToken", mappedBy="user", cascade={"remove"})
     */
    private Collection $accessTokens;

    public function __construct()
    {
        parent::__construct();

        // init values
        $this->accessTokens = new ArrayCollection();
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
        $this->updateLastUpdatedAt();

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

    public function updateName(string $name): self
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

    public function setEmail(string $email): self
    {
        $this->email = (new UnicodeString($email))->lower()->toString();

        return $this;
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

    public function setPassword(string $plainPassword, UserPasswordEncoderInterface $passwordEncoder): self
    {
        $this->password = $passwordEncoder->encodePassword($plainPassword);

        return $this;
    }

    public function updatePassword(string $plainPassword, UserPasswordEncoderInterface $passwordEncoder): self
    {
        $this->setPassword($plainPassword, $passwordEncoder);
        $this->updateLastUpdatedAt();

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

    public function updateImageDefined(bool $imageDefined): self
    {
        $this->setImageDefined($imageDefined);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
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

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function updateStatus(string $status): self
    {
        $this->setStatus($status);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getAccessTokens(): Collection
    {
        return $this->accessTokens;
    }

    public function addAccessToken(AccessToken $accessToken): self
    {
        $this->accessTokens[] = $accessToken;

        return $this;
    }

    public function removeAccessToken(AccessToken $accessToken): self
    {
        $this->accessTokens->removeElement($accessToken);

        return $this;
    }

    public function hasImage(): bool
    {
        return $this->isImageDefined();
    }

    public function getImageBasePath(): string
    {
        return 'user';
    }
}
