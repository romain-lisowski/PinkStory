<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Story\StoryFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Story\Domain\Model\Story;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StoryUpdateChildrenPositionActionTest extends AbstractStoryActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/story/update-children-position/'.StoryFixture::DATA['story-second']['id'];
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];
    }

    public function testSucceededSameUserLoggedIn(): void
    {
        $this->checkSucceeded([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
                StoryFixture::DATA['story-second-first']['id'],
            ],
        ]);
    }

    public function testSucceededDifferentUserLoggedInButAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
                StoryFixture::DATA['story-second-first']['id'],
            ],
        ]);
    }

    public function testSucceededDifferentUserLoggedInButModerator(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSucceeded([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
                StoryFixture::DATA['story-second-first']['id'],
            ],
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
                StoryFixture::DATA['story-second-first']['id'],
            ],
        ]);
    }

    public function testFailedNotFoundWrongFormatId(): void
    {
        // wrong uri format
        self::$httpUri = '/story/update-children-position/id';

        $this->checkFailedNotFound([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
                StoryFixture::DATA['story-second-first']['id'],
            ],
        ]);
    }

    public function testFailedNotFoundNonExistentId(): void
    {
        // non existent id
        self::$httpUri = '/story/update-children-position/'.Uuid::v4()->toRfc4122();

        $this->checkFailedNotFound([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
                StoryFixture::DATA['story-second-first']['id'],
            ],
        ]);
    }

    public function testFailedDifferentUserLoggedIn(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-juliette']['id'];

        $this->checkFailedAccessDenied([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
                StoryFixture::DATA['story-second-first']['id'],
            ],
        ]);
    }

    public function testFailedMissingChildren(): void
    {
        $this->checkFailedValidationFailed([], [
            'children_ids',
        ]);
    }

    public function testFailedMissingChildPosition(): void
    {
        $this->checkFailedValidationFailed([
            'children_ids' => [
                StoryFixture::DATA['story-second-second']['id'],
            ],
        ], [
            'children_ids',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // get fresh story from database
        $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['id'])->toRfc4122());
        $this->entityManager->refresh($story);

        // check story has been updated
        foreach ($story->getChildren() as $child) {
            // get fresh child from database
            $this->entityManager->refresh($child);

            if ($child->getId() === StoryFixture::DATA['story-second-first']['id']) {
                $this->assertEquals($child->getPosition(), 2);
            }

            if ($child->getId() === StoryFixture::DATA['story-second-second']['id']) {
                $this->assertEquals($child->getPosition(), 1);
            }
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals($story->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($story->extractChildrenPositionedIds(), $this->asyncTransport->get()[0]->getMessage()->getChildrenIds());
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // get fresh story from database
        $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-second']['id'])->toRfc4122());
        $this->entityManager->refresh($story);

        // check story has not been updated
        foreach ($story->getChildren() as $child) {
            // get fresh child from database
            $this->entityManager->refresh($child);

            if ($child->getId() === StoryFixture::DATA['story-second-first']['id']) {
                $this->assertEquals($child->getPosition(), 1);
            }

            if ($child->getId() === StoryFixture::DATA['story-second-second']['id']) {
                $this->assertEquals($child->getPosition(), 2);
            }
        }

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
