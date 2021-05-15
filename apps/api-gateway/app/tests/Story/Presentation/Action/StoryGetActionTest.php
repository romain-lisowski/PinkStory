<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Fixture\Language\LanguageFixture;
use App\Fixture\Story\StoryFixture;
use App\Fixture\Story\StoryImageFixture;
use App\Fixture\Story\StoryRatingFixture;
use App\Fixture\Story\StoryThemeFixture;
use App\Fixture\User\UserFixture;
use App\User\Domain\Model\UserGender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StoryGetActionTest extends AbstractStoryActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/story/'.StoryFixture::DATA['story-first']['id'];
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceededNoUserLoggedInButEnglishStory1(): void
    {
        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'story_expected_reference' => 'story-first',
        ]);
    }

    public function testSucceededNoUserLoggedInButEnglishStory2(): void
    {
        // change uri
        self::$httpUri = '/story/'.StoryFixture::DATA['story-second']['id'];

        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'story_expected_reference' => 'story-second',
        ]);
    }

    public function testSucceededNoUserLoggedInButEnglishStory22(): void
    {
        // change uri
        self::$httpUri = '/story/'.StoryFixture::DATA['story-second-first']['id'];

        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'story_expected_reference' => 'story-second-first',
        ]);
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

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $options['story_expected_reference'], $responseData['story']);

        // story informations
        $this->assertEquals(StoryFixture::DATA[$options['story_expected_reference']]['content'], $responseData['story']['content']);

        // story parent/children informations
        if (false === empty(StoryFixture::DATA[$options['story_expected_reference']]['parent_reference'])) {
            // check parent
            $this->checkProcessHasBeenSucceededTreatment($responseData, $options, StoryFixture::DATA[$options['story_expected_reference']]['parent_reference'], $responseData['story']['parent']);

            // check previous
            if (false === empty($options['story_previous_expected_reference'])) {
                $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $options['story_previous_expected_reference'], $responseData['story']['next']);
            }

            // check next
            if (false === empty($options['story_next_expected_reference'])) {
                $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $options['story_next_expected_reference'], $responseData['story']['next']);
            }
        } elseif (false === empty(StoryFixture::DATA[$options['story_expected_reference']]['children_reference'])) {
            // check children
            $this->assertCount(count(StoryFixture::DATA[$options['story_expected_reference']]['children_reference']), $responseData['story']['children']);

            foreach (StoryFixture::DATA[$options['story_expected_reference']]['children_reference'] as $storyChildFixtureReference) {
                $exists = false;

                foreach ($responseData['story']['children'] as $storyChildData) {
                    if ($storyChildData['id'] === StoryFixture::DATA[$storyChildFixtureReference]['id']) {
                        $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $storyChildFixtureReference, $storyChildData);

                        $exists = true;

                        break;
                    }
                }

                if (false === $exists) {
                    $this->fail('Story child does not exist.');
                }
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }

    private function checkProcessHasBeenSucceededTreatment(array $responseData = [], array $options = [], string $storyFixtureReference, array $storyData)
    {
        // story informations
        $this->assertEquals(StoryFixture::DATA[$storyFixtureReference]['id'], $storyData['id']);
        $this->assertEquals(StoryFixture::DATA[$storyFixtureReference]['title'], $storyData['title']);
        $this->assertEquals((new AsciiSlugger())->slug(StoryFixture::DATA[$storyFixtureReference]['title'])->lower()->toString(), $storyData['title_slug']);
        $this->assertEquals(StoryFixture::DATA[$storyFixtureReference]['extract'], $storyData['extract']);
        $this->assertLessThan(new \DateTime(), new \DateTime($storyData['created_at']));

        // story rating informations
        if (false === empty(StoryRatingFixture::DATA[$storyFixtureReference])) {
            $this->assertEquals(count(StoryRatingFixture::DATA[$storyFixtureReference]), $storyData['story_ratings_total']);
            $this->assertEquals($this->calculRate(StoryRatingFixture::DATA[$storyFixtureReference]), $storyData['rate']);
        } else {
            $this->assertEquals(0, $storyData['story_ratings_total']);
            $this->assertNull($storyData['rate']);
        }

        // user informations
        $this->assertEquals(UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['id'], $storyData['user']['id']);
        $this->assertEquals(UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['gender'], $storyData['user']['gender']);
        $this->assertEquals(self::$container->get(TranslatorInterface::class)->trans(strtolower(UserGender::getTranslationPrefix().UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['gender']), [], null, LanguageFixture::DATA[$options['language_reference']]['locale']), $storyData['user']['gender_reading']);
        $this->assertEquals(UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['name'], $storyData['user']['name']);
        $this->assertEquals((new AsciiSlugger())->slug(UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['name'])->lower()->toString(), $storyData['user']['name_slug']);
        $this->assertFalse($storyData['user']['image_defined']);
        $this->assertLessThan(new \DateTime(), new \DateTime($storyData['user']['created_at']));
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['language_reference']]['id'], $storyData['user']['language']['id']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['language_reference']]['title'], $storyData['user']['language']['title']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['language_reference']]['locale'], $storyData['user']['language']['locale']);

        // language informations
        $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['language_reference']]['id'], $storyData['language']['id']);
        $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['language_reference']]['title'], $storyData['language']['title']);
        $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['language_reference']]['locale'], $storyData['language']['locale']);

        // story image informations
        if (false === empty(StoryFixture::DATA[$storyFixtureReference]['story_image_reference'])) {
            $this->assertTrue(Uuid::isValid($storyData['story_image']['id']));
            $this->assertEquals(StoryImageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_image_reference']]['id'], $storyData['story_image']['id']);
            $this->assertEquals(StoryImageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_image_reference']]['translations'][$options['language_reference']]['title'], $storyData['story_image']['title']);
            $this->assertEquals((new AsciiSlugger())->slug(StoryImageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_image_reference']]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyData['story_image']['title_slug']);
        } else {
            $this->assertNull($storyData['story_image']);
        }

        // story themes informations
        $this->assertCount(count(StoryFixture::DATA[$storyFixtureReference]['story_themes_reference']), $storyData['story_themes']);

        foreach ($storyData['story_themes'] as $i => $storyThemeData) {
            $this->assertEquals(StoryThemeFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_themes_reference'][$i]]['id'], $storyThemeData['id']);
            $this->assertEquals(StoryThemeFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_themes_reference'][$i]]['translations'][$options['language_reference']]['title'], $storyThemeData['title']);
            $this->assertEquals((new AsciiSlugger())->slug(StoryThemeFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_themes_reference'][$i]]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyThemeData['title_slug']);
        }
    }
}
