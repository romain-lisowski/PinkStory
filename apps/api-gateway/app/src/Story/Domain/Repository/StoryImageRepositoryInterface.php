<?php

declare(strict_types=1);

namespace App\Story\Domain\Repository;

use App\Common\Domain\Repository\RepositoryInterface;
use App\Story\Domain\Model\StoryImage;

interface StoryImageRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws StoryImageNoResultException
     */
    public function findOne(string $id): StoryImage;
}
