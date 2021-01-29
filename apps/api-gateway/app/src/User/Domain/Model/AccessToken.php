<?php

declare(strict_types=1);

namespace App\User\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\User\Infrastructure\Doctrine\Repository\AccessTokenDoctrineORMRepository")
 * @ORM\Table(name="usr_access_token")
 */
class AccessToken extends AbstractEntity implements UserableInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="App\User\Domain\Model\User", inversedBy="accessTokens")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addAccessToken($this);

        return $this;
    }
}
