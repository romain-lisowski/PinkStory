<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Story\StoryFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StoryRatingUpdateActionTest extends AbstractStoryRatingActionTest
{
    private static array $storyRatingData = [
        'rate' => 1,
    ];

    private int $rate;

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/story-rating/'.StoryFixture::DATA['story-first']['id'];
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        // get story rating data
        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser(Uuid::fromString(StoryFixture::DATA['story-first']['id'])->toRfc4122(), Uuid::fromString(UserFixture::DATA['user-john']['id'])->toRfc4122());
        $this->entityManager->refresh($storyRating);
        $this->rate = $storyRating->getRate();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
            'rate' => self::$storyRatingData['rate'],
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'rate' => self::$storyRatingData['rate'],
        ]);
    }

    public function testFailedNotFoundWrongFormatId(): void
    {
        // wrong uri format
        self::$httpUri = '/story-rating/id';

        $this->checkFailedNotFound([
            'rate' => self::$storyRatingData['rate'],
        ]);
    }

    public function testFailedNotFoundNonExistentId(): void
    {
        // non existent id
        self::$httpUri = '/story-rating/'.Uuid::v4()->toRfc4122();

        $this->checkFailedNotFound([
            'rate' => self::$storyRatingData['rate'],
        ]);
    }

    public function testFailedMissingRate(): void
    {
        $this->checkFailedMissingMandatory();
    }

    public function testFailedWrongRate(): void
    {
        $this->checkFailedValidationFailed([
            'rate' => 10,
        ], [
            'rate',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // get fresh story rating from database
        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser(Uuid::fromString(StoryFixture::DATA['story-first']['id'])->toRfc4122(), Uuid::fromString(UserFixture::DATA['user-john']['id'])->toRfc4122());
        $this->entityManager->refresh($storyRating);

        // check story has been updated
        $this->assertEquals(self::$storyRatingData['rate'], $storyRating->getRate());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(StoryFixture::DATA['story-first']['id'], $this->asyncTransport->get()[0]->getMessage()->getStoryId());
        $this->assertEquals(UserFixture::DATA['user-john']['id'], $this->asyncTransport->get()[0]->getMessage()->getUserId());
        $this->assertEquals($storyRating->getRate(), $this->asyncTransport->get()[0]->getMessage()->getRate());
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // get fresh story rating from database
        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser(Uuid::fromString(StoryFixture::DATA['story-first']['id'])->toRfc4122(), Uuid::fromString(UserFixture::DATA['user-john']['id'])->toRfc4122());
        $this->entityManager->refresh($storyRating);

        // check story has not been updated
        $this->assertEquals($this->rate, $storyRating->getRate());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
