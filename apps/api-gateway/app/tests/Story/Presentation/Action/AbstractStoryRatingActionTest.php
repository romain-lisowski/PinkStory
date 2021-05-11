<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Story\Domain\Model\StoryRating;
use App\Story\Infrastructure\Doctrine\Repository\StoryRatingDoctrineORMRepository;
use App\Test\Common\Presentation\Action\AbstractActionTest;

/**
 * @internal
 * @coversNothing
 */
abstract class AbstractStoryRatingActionTest extends AbstractActionTest
{
    protected StoryRatingDoctrineORMRepository $storyRatingRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storyRatingRepository = self::$container->get('doctrine')->getManager()->getRepository(StoryRating::class);
    }
}
