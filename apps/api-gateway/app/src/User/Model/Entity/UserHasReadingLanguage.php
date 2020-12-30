<?php

declare(strict_types=1);

namespace App\User\Model\Entity;

use App\Language\Model\Entity\Language;
use App\Model\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="usr_user_has_reading_language", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_USER_HAS_READING_LANGUAGE", columns={"user_id", "language_id"})})
 * @ORM\Entity(repositoryClass="App\User\Repository\Entity\UserHasReadingLanguageRepository")
 * @UniqueEntity(
 *      fields = {"user", "language"}
 * )
 */
class UserHasReadingLanguage extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\User\Model\Entity\User", inversedBy="userHasReadingLanguages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Model\Entity\Language", inversedBy="userHasReadingLanguages")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private Language $language;

    public function __construct(User $user, Language $language)
    {
        parent::__construct();

        // init values
        $this->setUser($user)
            ->setLanguage($language)
        ;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addUserHasReadingLanguage($this);

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;
        $language->addUserHasReadingLanguage($this);

        return $this;
    }
}
