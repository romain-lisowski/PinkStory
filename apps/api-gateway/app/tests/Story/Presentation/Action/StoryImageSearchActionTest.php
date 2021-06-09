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
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/story-image/search';
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceededNoSearchNoLogginButEnglish(): void
    {
        $this->checkSucceeded(null, [
            'language_reference' => 'language-english',
            'total_count' => 3,
            'story_images_expected_reference' => [
                'story-image-third',
                'story-image-second',
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededNoSearchNoLogginButFrench(): void
    {
        // change locale
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            '_locale' => 'fr',
        ]);

        $this->checkSucceeded(null, [
            'language_reference' => 'language-french',
            'total_count' => 3,
            'story_images_expected_reference' => [
                'story-image-third',
                'story-image-second',
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededNoSearchLogginEnglish(): void
    {
        // change locale (force to test user setting override)
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            '_locale' => 'fr',
        ]);

        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded(null, [
            'language_reference' => UserFixture::DATA['user-john']['language_reference'],
            'total_count' => 3,
            'story_images_expected_reference' => [
                'story-image-third',
                'story-image-second',
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededNoSearchLogginFrench(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        $this->checkSucceeded(null, [
            'language_reference' => UserFixture::DATA['user-pinkstory']['language_reference'],
            'total_count' => 3,
            'story_images_expected_reference' => [
                'story-image-third',
                'story-image-second',
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededSearchEnglish(): void
    {
        // change uri
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-bdsm-domination']['id'],
            ],
        ]);

        $this->checkSucceeded(null, [
            'language_reference' => 'language-english',
            'total_count' => 1,
            'story_images_expected_reference' => [
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededSearch2English(): void
    {
        // change uri
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-extreme']['id'],
            ],
        ]);

        $this->checkSucceeded(null, [
            'language_reference' => 'language-english',
            'total_count' => 2,
            'story_images_expected_reference' => [
                'story-image-third',
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededSearch3English(): void
    {
        // change uri
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-bdsm-domination']['id'],
                StoryThemeFixture::DATA['story-theme-extreme']['id'],
            ],
        ]);

        $this->checkSucceeded(null, [
            'language_reference' => 'language-english',
            'total_count' => 1,
            'story_images_expected_reference' => [
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededSearch4French(): void
    {
        // change locale
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            '_locale' => 'fr',
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-bdsm-domination']['id'],
                StoryThemeFixture::DATA['story-theme-extreme']['id'],
            ],
        ]);

        $this->checkSucceeded(null, [
            'language_reference' => 'language-french',
            'total_count' => 1,
            'story_images_expected_reference' => [
                'story-image-first',
            ],
        ]);
    }

    public function testSucceededSearch5English(): void
    {
        // change uri
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-extreme']['id'],
            ],
            'limit' => 1,
        ]);

        $this->checkSucceeded(null, [
            'language_reference' => 'language-english',
            'total_count' => 2,
            'story_images_expected_reference' => [
                'story-image-third',
            ],
        ]);
    }

    public function testSucceededSearch6English(): void
    {
        // change uri
        self::$httpUri = $this->httpBuild(self::$httpUri, [
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-extreme']['id'],
            ],
            'limit' => 1,
            'offset' => 2,
        ]);

        $this->checkSucceeded(null, [
            'language_reference' => 'language-english',
            'total_count' => 2,
            'story_images_expected_reference' => [],
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertEquals($options['total_count'], $responseData['story_images_total']);
        $this->assertCount(count($options['story_images_expected_reference']), $responseData['story_images']);

        foreach ($responseData['story_images'] as $i => $storyImageData) {
            $this->assertEquals(StoryImageFixture::DATA[$options['story_images_expected_reference'][$i]]['id'], $storyImageData['id']);
            $this->assertEquals(StoryImageFixture::DATA[$options['story_images_expected_reference'][$i]]['translations'][$options['language_reference']]['title'], $storyImageData['title']);
            $this->assertEquals((new AsciiSlugger())->slug(StoryImageFixture::DATA[$options['story_images_expected_reference'][$i]]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyImageData['title_slug']);
            $this->assertIsString($storyImageData['image_url']);

            $this->assertCount(count(StoryImageFixture::DATA[$options['story_images_expected_reference'][$i]]['story_themes_reference']), $storyImageData['story_themes']);

            foreach ($storyImageData['story_themes'] as $j => $storyThemeData) {
                $this->assertEquals(StoryThemeFixture::DATA[StoryImageFixture::DATA[$options['story_images_expected_reference'][$i]]['story_themes_reference'][$j]]['id'], $storyThemeData['id']);
                $this->assertEquals(StoryThemeFixture::DATA[StoryImageFixture::DATA[$options['story_images_expected_reference'][$i]]['story_themes_reference'][$j]]['translations'][$options['language_reference']]['title'], $storyThemeData['title']);
                $this->assertEquals((new AsciiSlugger())->slug(StoryThemeFixture::DATA[StoryImageFixture::DATA[$options['story_images_expected_reference'][$i]]['story_themes_reference'][$j]]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyThemeData['title_slug']);
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
