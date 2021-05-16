<?php

declare(strict_types=1);

namespace App\Common\Domain\Repository;

use App\Common\Domain\Model\AbstractEntity;

interface RepositoryInterface
{
    public function persist(AbstractEntity $entity): void;

    public function remove(AbstractEntity $entity): void;

    public function flush(): void;
}
