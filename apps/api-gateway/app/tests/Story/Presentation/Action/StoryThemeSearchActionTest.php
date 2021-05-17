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
            'editable' => false,
            'language_reference' => 'language-english',
        ]);
    }

    public function testSucceededNoLogginButFrench(): void
    {
        // change locale
        self::$httpUri = self::$httpUri.'?_locale=fr';

        $this->checkSucceeded([], [
            'editable' => false,
            'language_reference' => 'language-french',
        ]);
    }

    public function testSucceededLogginEnglish(): void
    {
        // change locale (force to test user setting override)
        self::$httpUri = self::$httpUri.'?_locale=fr';

        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded([], [
            'editable' => false,
            'language_reference' => UserFixture::DATA['user-john']['language_reference'],
        ]);
    }

    public function testSucceededLogginFrench(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        $this->checkSucceeded([], [
            'editable' => true,
            'language_reference' => UserFixture::DATA['user-pinkstory']['language_reference'],
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $storyThemeParentFixturesReferences = [];

        foreach (StoryThemeFixture::DATA as $storyThemeFixtureReference => $storyThemeFixture) {
            if (true === empty($storyThemeFixture['parent_reference'])) {
                $storyThemeParentFixturesReferences[] = $storyThemeFixtureReference;
            }
        }

        $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $storyThemeParentFixturesReferences, $responseData['story_themes']);
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }

    private function checkProcessHasBeenSucceededTreatment(array $responseData = [], array $options = [], array $storyThemeFixtureReferences, array $storyThemesData)
    {
        $this->assertCount(count($storyThemeFixtureReferences), $storyThemesData);

        foreach ($storyThemesData as $i => $storyThemeData) {
            $this->assertEquals(StoryThemeFixture::DATA[$storyThemeFixtureReferences[$i]]['id'], $storyThemeData['id']);
            $this->assertEquals(StoryThemeFixture::DATA[$storyThemeFixtureReferences[$i]]['translations'][$options['language_reference']]['title'], $storyThemeData['title']);
            $this->assertEquals((new AsciiSlugger())->slug(StoryThemeFixture::DATA[$storyThemeFixtureReferences[$i]]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyThemeData['title_slug']);
            $this->assertEquals($options['editable'], $storyThemeData['editable']);

            if (false === empty($data['children_reference'])) {
                $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $storyThemeData['children_reference'], $storyThemeData['children']);
            }
        }
    }
}
