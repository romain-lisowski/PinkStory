<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Story\Domain\Model\Story;
use App\Story\Infrastructure\Doctrine\Repository\StoryDoctrineORMRepository;
use App\Test\Common\Presentation\Action\AbstractActionTest;

/**
 * @internal
 * @coversNothing
 */
abstract class AbstractStoryActionTest extends AbstractActionTest
{
    protected StoryDoctrineORMRepository $storyRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storyRepository = self::$container->get('doctrine')->getManager()->getRepository(Story::class);
    }

    protected function calculRate($storyRatingFixtures): ?float
    {
        $rates = array_map(function (array $storyRatingFixture) {
            return $storyRatingFixture['rate'];
        }, $storyRatingFixtures);

        if (0 === count($rates)) {
            return null;
        }

        return round(array_sum($rates) / count($rates), 1);
    }
}
