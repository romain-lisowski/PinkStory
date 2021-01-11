<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Repository;

use App\Common\Infrastructure\Doctrine\Repository\AbstractDoctrineORMRepository;
use App\User\Domain\Repository\UserRepositoryInterface;

final class UserDoctrineORMRepository extends AbstractDoctrineORMRepository implements UserRepositoryInterface
{
}
