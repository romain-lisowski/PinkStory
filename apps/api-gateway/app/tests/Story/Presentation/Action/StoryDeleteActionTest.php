<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Story\StoryFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Story\Domain\Repository\StoryNoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StoryDeleteActionTest extends AbstractStoryActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_DELETE;
        self::$httpUri = '/story/'.StoryFixture::DATA['story-second']['id'];
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];
    }

    public function testSucceededSameUserLoggedIn(): void
    {
        $this->checkSucceeded();
    }

    public function testSucceededDifferentUserLoggedInButAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded();
    }

    public function testSucceededDifferentUserLoggedInButModerator(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSucceeded();
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    public function testFailedNotFoundWrongFormatId(): void
    {
        // wrong uri format
        self::$httpUri = '/story/id';

        $this->checkFailedNotFound();
    }

    public function testFailedNotFoundNonExistentId(): void
    {
        // non existent id
        self::$httpUri = '/story/'.Uuid::v4()->toRfc4122();

        $this->checkFailedNotFound();
    }

    public function testFailedDifferentUserLoggedIn(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-juliette']['id'];

        $this->checkFailedAccessDenied();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        try {
            // check story has been deleted
            $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['id'])->toRfc4122());

            $this->fail();
        } catch (StoryNoResultException $e) {
            $this->assertTrue(true);

            // check event has been dispatched
            $this->assertCount(1, $this->asyncTransport->get());
            $this->assertEquals(StoryFixture::DATA['story-second']['id'], $this->asyncTransport->get()[0]->getMessage()->getId());
        }

        try {
            // check story has been deleted - cascade
            $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['children']['story-second-first']['id'])->toRfc4122());

            $this->fail();
        } catch (StoryNoResultException $e) {
            $this->assertTrue(true);
        }

        try {
            // check story has been deleted - cascade
            $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['children']['story-second-second']['id'])->toRfc4122());

            $this->fail();
        } catch (StoryNoResultException $e) {
            $this->assertTrue(true);
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        try {
            // check story has not been deleted
            $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['id'])->toRfc4122());

            // check story has not been deleted
            $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['children']['story-second-first']['id'])->toRfc4122());

            // check story has not been deleted
            $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['children']['story-second-second']['id'])->toRfc4122());

            $this->assertTrue(true);

            // check event has not been dispatched
            $this->assertCount(0, $this->asyncTransport->get());
        } catch (StoryNoResultException $e) {
            $this->fail();
        }
    }
}
