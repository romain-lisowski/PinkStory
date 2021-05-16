<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\RepositoryInterface;
use App\Story\Domain\Model\Story;

interface StoryRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws StoryNoResultException
     */
    public function findOne(string $id): Story;
}
