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
final class StoryRatingDeleteActionTest extends AbstractStoryRatingActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_DELETE;
        self::$httpUri = '/story-rating/'.StoryFixture::DATA['story-first']['id'];
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded();
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    public function testFailedNotFoundWrongFormatId(): void
    {
        // wrong uri format
        self::$httpUri = '/story-rating/id';

        $this->checkFailedNotFound();
    }

    public function testFailedNotFoundNonExistentId(): void
    {
        // non existent id
        self::$httpUri = '/story-rating/'.Uuid::v4()->toRfc4122();

        $this->checkFailedNotFound();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // get fresh story rating from database
        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser(Uuid::fromString(StoryFixture::DATA['story-first']['id'])->toRfc4122(), Uuid::fromString(UserFixture::DATA['user-john']['id'])->toRfc4122());

        // check story rating has been deleted
        if (null !== $storyRating) {
            $this->fail();
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(StoryFixture::DATA['story-first']['id'], $this->asyncTransport->get()[0]->getMessage()->getStoryId());
        $this->assertEquals(UserFixture::DATA['user-john']['id'], $this->asyncTransport->get()[0]->getMessage()->getUserId());
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // get fresh story rating from database
        $storyRating = $this->storyRatingRepository->findOneByStoryAndUser(Uuid::fromString(StoryFixture::DATA['story-first']['id'])->toRfc4122(), Uuid::fromString(UserFixture::DATA['user-john']['id'])->toRfc4122());

        // check story rating has not been deleted
        if (null === $storyRating) {
            $this->fail();
        }

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
