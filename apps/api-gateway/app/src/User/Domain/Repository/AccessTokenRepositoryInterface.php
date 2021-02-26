<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\Common\Domain\Repository\NoResultException;
use App\Common\Domain\Repository\RepositoryInterface;
use App\User\Domain\Model\AccessToken;

interface AccessTokenRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws NoResultException
     */
    public function findOne(string $id): AccessToken;
}
