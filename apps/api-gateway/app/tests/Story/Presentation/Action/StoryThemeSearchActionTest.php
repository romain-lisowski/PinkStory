<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Story\StoryThemeFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @internal
 * @coversNothing
 */
final class StoryThemeSearchActionTest extends AbstractStoryThemeActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/story-theme/search';
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceededNoLogginButEnglish(): void
    {
        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
        ]);
    }

    public function testSucceededNoLogginButFrench(): void
    {
        // change locale
        self::$httpUri = '/story-theme/search?_locale=fr';

        $this->checkSucceeded([], [
            'language_reference' => 'language-french',
        ]);
    }

    public function testSucceededLogginEnglish(): void
    {
        // change locale (force to test user setting override)
        self::$httpUri = '/story-theme/search?_locale=fr';

        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-john']['language_reference'],
        ]);
    }

    public function testSucceededLogginFrench(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-pinkstory']['language_reference'],
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $storyThemeFixtures = array_values(StoryThemeFixture::DATA);

        $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $storyThemeFixtures, $responseData['story_themes']);
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }

    private function checkProcessHasBeenSucceededTreatment(array $responseData = [], array $options = [], array $storyThemeFixtures, array $storyThemesData)
    {
        $this->assertCount(count($storyThemeFixtures), $storyThemesData);

        foreach ($storyThemesData as $i => $data) {
            $this->assertEquals($storyThemeFixtures[$i]['id'], $data['id']);
            $this->assertEquals($storyThemeFixtures[$i]['translations'][$options['language_reference']]['title'], $data['title']);
            $this->assertEquals((new AsciiSlugger())->slug($storyThemeFixtures[$i]['translations'][$options['language_reference']]['title'])->lower()->toString(), $data['title_slug']);

            if (true === isset($data['children'])) {
                $this->checkProcessHasBeenSucceededTreatment($responseData, $options, array_values($storyThemeFixtures[$i]['children']), $data['children']);
            }
        }
    }
}
