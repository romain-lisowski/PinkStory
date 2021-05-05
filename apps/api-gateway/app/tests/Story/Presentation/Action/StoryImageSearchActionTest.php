<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Story\StoryImageFixture;
use App\Fixture\Story\StoryThemeFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @internal
 * @coversNothing
 */
final class StoryImageSearchActionTest extends AbstractStoryImageActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/story-image/search';
        self::$httpAuthorization = null;
    }

    public function testSucceededNoSearchNoLogginButEnglish(): void
    {
        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'count' => 3,
            'total_count' => 3,
        ]);
    }

    public function testSucceededNoSearchNoLogginButFrench(): void
    {
        // change locale
        self::$httpUri = '/story-image/search?_locale=fr';

        $this->checkSucceeded([], [
            'language_reference' => 'language-french',
            'count' => 3,
            'total_count' => 3,
        ]);
    }

    public function testSucceededNoSearchLogginEnglish(): void
    {
        // change locale (force to test user setting override)
        self::$httpUri = '/story-image/search?_locale=fr';

        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-john']['language_reference'],
            'count' => 3,
            'total_count' => 3,
        ]);
    }

    public function testSucceededNoSearchLogginFrench(): void
    {
        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-pinkstory']['language_reference'],
            'count' => 3,
            'total_count' => 3,
        ]);
    }

    public function testSucceededSearchEnglish(): void
    {
        $this->checkSucceeded([
            'story_theme_ids' => [
                'd43da482-c48a-47a4-ad02-c18f3a93b024',
            ],
        ], [
            'language_reference' => 'language-english',
            'count' => 1,
            'total_count' => 1,
        ]);
    }

    public function testSucceededSearch2English(): void
    {
        $this->checkSucceeded([
            'story_theme_ids' => [
                '51440b44-b4c9-4b1b-b10d-0ba749d5e316',
            ],
        ], [
            'language_reference' => 'language-english',
            'count' => 2,
            'total_count' => 2,
        ]);
    }

    public function testSucceededSearch3English(): void
    {
        $this->checkSucceeded([
            'story_theme_ids' => [
                'd43da482-c48a-47a4-ad02-c18f3a93b024',
                '51440b44-b4c9-4b1b-b10d-0ba749d5e316',
            ],
        ], [
            'language_reference' => 'language-english',
            'count' => 1,
            'total_count' => 1,
        ]);
    }

    public function testSucceededSearch4French(): void
    {
        // change locale
        self::$httpUri = '/story-image/search?_locale=fr';

        $this->checkSucceeded([
            'story_theme_ids' => [
                'd43da482-c48a-47a4-ad02-c18f3a93b024',
                '51440b44-b4c9-4b1b-b10d-0ba749d5e316',
            ],
        ], [
            'language_reference' => 'language-french',
            'count' => 1,
            'total_count' => 1,
        ]);
    }

    public function testSucceededSearch5English(): void
    {
        $this->checkSucceeded([
            'story_theme_ids' => [
                '51440b44-b4c9-4b1b-b10d-0ba749d5e316',
            ],
            'limit' => 1,
        ], [
            'language_reference' => 'language-english',
            'count' => 1,
            'total_count' => 2,
        ]);
    }

    public function testSucceededSearch6English(): void
    {
        $this->checkSucceeded([
            'story_theme_ids' => [
                '51440b44-b4c9-4b1b-b10d-0ba749d5e316',
            ],
            'limit' => 1,
            'offset' => 2,
        ], [
            'language_reference' => 'language-english',
            'count' => 0,
            'total_count' => 2,
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $storyImageFixtures = array_values(StoryImageFixture::DATA);

        $this->assertEquals($options['total_count'], $responseData['story-images-total']);
        $this->assertCount($options['count'], $responseData['story-images']);

        foreach ($responseData['story-images'] as $storyImageData) {
            $storyImageExists = false;

            foreach ($storyImageFixtures as $storyImageFixture) {
                if ($storyImageFixture['id'] === $storyImageData['id']) {
                    $this->assertEquals($storyImageFixture['id'], $storyImageData['id']);
                    $this->assertEquals($storyImageFixture['translations'][$options['language_reference']]['title'], $storyImageData['title']);
                    $this->assertEquals((new AsciiSlugger())->slug($storyImageFixture['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyImageData['title_slug']);

                    $this->assertCount(count($storyImageFixture['story-themes']), $storyImageData['story_themes']);

                    foreach ($storyImageData['story_themes'] as $storyThemeData) {
                        $storyThemeExists = false;

                        foreach (StoryThemeFixture::DATA as $parentStoryThemeFixture) {
                            foreach ($parentStoryThemeFixture['children'] as $childStoryThemeFixture) {
                                if ($childStoryThemeFixture['id'] === $storyThemeData['id']) {
                                    $this->assertEquals($childStoryThemeFixture['translations'][$options['language_reference']]['title'], $storyThemeData['title']);
                                    $this->assertEquals((new AsciiSlugger())->slug($childStoryThemeFixture['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyThemeData['title_slug']);

                                    $storyThemeExists = true;

                                    break 2;
                                }
                            }
                        }

                        if (false === $storyThemeExists) {
                            $this->fail('Story theme does not exist.');
                        }
                    }

                    $storyImageExists = true;

                    break;
                }
            }

            if (false === $storyImageExists) {
                $this->fail('Story image does not exist.');
            }
        }
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }
}
