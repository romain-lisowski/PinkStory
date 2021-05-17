<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
use App\Fixture\Story\StoryFixture;
use App\Fixture\Story\StoryImageFixture;
use App\Fixture\Story\StoryThemeFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StoryGetForUpdateActionTest extends AbstractStoryActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/story/update/'.StoryFixture::DATA['story-first']['id'];
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        parent::setUp();
    }

    public function testSucceededSameUserLoggedIn(): void
    {
        $this->checkSucceeded([], [
            'editable' => true,
            'user_editable' => true,
        ]);
    }

    public function testSucceededDifferentUserLoggedInButAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded([], [
            'editable' => true,
            'user_editable' => true,
        ]);
    }

    public function testSucceededDifferentUserLoggedInButModerator(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSucceeded([], [
            'editable' => true,
            'user_editable' => true,
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    public function testFailedNotFoundWrongFormatId(): void
    {
        // wrong uri format
        self::$httpUri = '/story/update/id';

        $this->checkFailedNotFound();
    }

    public function testFailedNotFoundNonExistentId(): void
    {
        // non existent id
        self::$httpUri = '/story/update/'.Uuid::v4()->toRfc4122();

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
        // story informations
        $this->assertEquals(StoryFixture::DATA['story-first']['id'], $responseData['story']['id']);
        $this->assertEquals(StoryFixture::DATA['story-first']['title'], $responseData['story']['title']);
        $this->assertEquals(StoryFixture::DATA['story-first']['extract'], $responseData['story']['extract']);
        $this->assertEquals(StoryFixture::DATA['story-first']['content'], $responseData['story']['content']);
        $this->assertEquals($options['editable'], $responseData['story']['editable']);

        // user informations
        $this->assertEquals(UserFixture::DATA[StoryFixture::DATA['story-first']['user_reference']]['id'], $responseData['story']['user']['id']);
        $this->assertEquals($options['user_editable'], $responseData['story']['user']['editable']);

        // language informations
        $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA['story-first']['language_reference']]['id'], $responseData['story']['language']['id']);

        // story image informations
        if (false === empty(StoryFixture::DATA['story-first']['story_image_reference'])) {
            $this->assertEquals(StoryImageFixture::DATA[StoryFixture::DATA['story-first']['story_image_reference']]['id'], $responseData['story']['story_image']['id']);
        } else {
            $this->assertNull($responseData['story']['story_image']);
        }

        // story themes informations
        $this->assertCount(count(StoryFixture::DATA['story-first']['story_themes_reference']), $responseData['story']['story_themes']);

        foreach (StoryFixture::DATA['story-first']['story_themes_reference'] as $storyThemeFixtureReference) {
            $exists = false;

            foreach ($responseData['story']['story_themes'] as $i => $storyThemeData) {
                if (StoryThemeFixture::DATA[$storyThemeFixtureReference]['id'] === $storyThemeData['id']) {
                    $exists = true;

                    break;
                }
            }

            if (false === $exists) {
                $this->fail('Story theme ['.StoryThemeFixture::DATA[$storyThemeFixtureReference]['id'].'] does not exist.');
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
