<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use App\Language\Domain\Model\Language;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\User\Infrastructure\Doctrine\Repository\UserHasReadingLanguageDoctrineORMRepository")
 * @ORM\Table(name="usr_user_has_reading_language", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_USER_HAS_READING_LANGUAGE", columns={"user_id", "language_id"})})
 * @UniqueEntity(
 *      fields = {"user", "language"}
 * )
 */
class UserHasReadingLanguage extends AbstractEntity implements UserableInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="App\User\Domain\Model\User", inversedBy="userHasReadingLanguages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Language\Domain\Model\Language", inversedBy="userHasReadingLanguages")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private Language $language;

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
