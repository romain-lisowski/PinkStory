<?php

declare(strict_types=1);

namespace App\User\Model\Entity;

use App\File\Model\ImageableInterface;
use App\File\Model\ImageableTrait;
use App\Language\Model\Entity\Language;
use App\Language\Model\Entity\LanguageableInterface;
use App\Language\Model\Entity\LanguageableTrait;
use App\Language\Model\LanguageInterface;
use App\Model\Entity\AbstractEntity;
use App\Story\Model\Entity\Story;
use App\Story\Model\Entity\StoryRating;
use App\User\Model\UserGender;
use App\User\Model\UserInterface as ModelUserInterface;
use App\User\Model\UserRole;
use App\User\Model\UserStatus;
use App\User\Validator\Constraints as AppUserAssert;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="usr_user")
 * @ORM\Entity(repositoryClass="App\User\Repository\Entity\UserRepository")
 * @UniqueEntity(
 *      fields = {"email"}
 * )
 */
class User extends AbstractEntity implements UserInterface, ModelUserInterface, UserEditableInterface, ImageableInterface, LanguageableInterface
{
    use UserEditableTrait;
    use ImageableTrait;
    use LanguageableTrait;

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
     * @Assert\Choice(callback={"App\User\Model\UserGender", "getChoices"})
     * @ORM\Column(name="gender", type="string", length=255)
     */
    private string $gender;

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
     * @ORM\Column(name="email_validation_code", type="string", length=255)
     */
    private string $emailValidationCode;

    /**
     * @Assert\NotNull
     * @ORM\Column(name="email_validation_code_used", type="boolean")
     */
    private bool $emailValidationCodeUsed;

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

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="status", type="string", length=255)
     */
    private string $status;

    /**
     * @Assert\NotNull
     * @ORM\Column(name="image_defined", type="boolean")
     */
    private bool $imageDefined;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Model\Entity\Language", inversedBy="users")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private LanguageInterface $language;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Model\Entity\Story", mappedBy="user", cascade={"remove"})
     * @ORM\OrderBy({"title" = "ASC"})
     */
    private Collection $stories;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Model\Entity\StoryRating", mappedBy="user", cascade={"remove"})
     */
    private Collection $storyRatings;

    public function __construct(string $name = '', string $gender = UserGender::UNDEFINED, string $email = '', string $role = UserRole::ROLE_USER, string $status = UserStatus::ACTIVATED, Language $language)
    {
        parent::__construct();

        // init zero values
        $this->name = '';
        $this->nameSlug = '';
        $this->gender = UserGender::UNDEFINED;
        $this->email = '';
        $this->emailValidated = false;
        $this->emailValidationCode = sprintf('%06d', random_int(0, 999999));
        $this->emailValidationCodeUsed = false;
        $this->password = '';
        $this->passwordForgottenSecret = Uuid::v4()->toRfc4122();
        $this->passwordForgottenSecretUsed = true; // not claimed by user at account creation, so block it until new claim
        $this->passwordForgottenSecretCreatedAt = new DateTime();
        $this->secret = Uuid::v4()->toRfc4122();
        $this->role = UserRole::ROLE_USER;
        $this->status = UserStatus::ACTIVATED;
        $this->imageDefined = false;
        $this->stories = new ArrayCollection();
        $this->storyRatings = new ArrayCollection();

        // init values
        $this->setName($name)
            ->setGender($gender)
            ->setEmail($email)
            ->setRole($role)
            ->setStatus($status)
            ->setLanguage($language)
        ;
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
        $this->regenerateEmailValidationCode();

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
        $this->setEmailValidationCodeUsed(true);

        return $this;
    }

    public function getEmailValidationCode(): string
    {
        return $this->emailValidationCode;
    }

    public function setEmailValidationCode(string $code): self
    {
        $this->emailValidationCode = $code;

        return $this;
    }

    public function regenerateEmailValidationCode(): self
    {
        $this->setEmailValidationCode(sprintf('%06d', random_int(0, 999999)));
        $this->setEmailValidationCodeUsed(false);
        $this->setEmailValidated(false);

        return $this;
    }

    public function isEmailValidationCodeUsed(): bool
    {
        return $this->emailValidationCodeUsed;
    }

    public function setEmailValidationCodeUsed(bool $used): self
    {
        $this->emailValidationCodeUsed = $used;

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

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function updateRole(string $role): self
    {
        $this->setRole($role);

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

        return $this;
    }

    public function hasImageDefined(): bool
    {
        return $this->imageDefined;
    }

    public function setImageDefined(bool $imageDefined): self
    {
        $this->imageDefined = $imageDefined;

        return $this;
    }

    public function getUser(): ModelUserInterface
    {
        return $this;
    }

    public function setUser(ModelUserInterface $user): self
    {
        return $this;
    }

    public function hasImage(): bool
    {
        return $this->imageDefined;
    }

    public function deleteImage(): self
    {
        $this->setImageDefined(false);

        return $this;
    }

    public function getImageBasePath(): string
    {
        return 'user';
    }

    public function setLanguage(LanguageInterface $language): self
    {
        if (!$language instanceof Language) {
            throw new InvalidArgumentException();
        }

        $this->language = $language;
        $language->addUser($this);

        return $this;
    }

    public function updateLanguage(LanguageInterface $language): self
    {
        if ($this->language instanceof Language) {
            $this->language->removeUser($this);
        }

        $this->setLanguage($language);

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

    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Story $story): self
    {
        $this->stories[] = $story;

        return $this;
    }

    public function removeStory(Story $story): self
    {
        $this->stories->removeElement($story);

        return $this;
    }

    public function getStoryRatings(): Collection
    {
        return $this->storyRatings;
    }

    public function addStoryRating(StoryRating $storyRating): self
    {
        $this->storyRatings[] = $storyRating;

        return $this;
    }

    public function removeStoryRating(StoryRating $storyRating): self
    {
        $this->storyRatings->removeElement($storyRating);

        return $this;
    }
}
