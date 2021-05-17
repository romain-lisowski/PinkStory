<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Fixture\Language\LanguageFixture;
use App\Fixture\Story\StoryFixture;
use App\Fixture\Story\StoryImageFixture;
use App\Fixture\Story\StoryRatingFixture;
use App\Fixture\Story\StoryThemeFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use App\Story\Query\Query\StorySearchQuery;
use App\User\Domain\Model\UserGender;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StorySearchActionTest extends AbstractStoryActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/story/search';
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceededNoLogginButEnglish(): void
    {
        $this->checkSucceeded([
            'order' => StorySearchQuery::ORDER_CREATED_AT,
            'sort' => Criteria::ASC,
        ], [
            'language_reference' => 'language-english',
            'total_count' => 4,
            'stories_expected' => [
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButFrench(): void
    {
        // change locale
        self::$httpUri = self::$httpUri.'?_locale=fr';

        $this->checkSucceeded([
            'order' => StorySearchQuery::ORDER_CREATED_AT,
            'sort' => Criteria::ASC,
        ], [
            'language_reference' => 'language-french',
            'total_count' => 2,
            'stories_expected' => [
                [
                    'reference' => 'story-third',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-fourth',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededLogginEnglish(): void
    {
        // change locale (force to test user setting override)
        self::$httpUri = self::$httpUri.'?_locale=fr';

        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded([
            'order' => StorySearchQuery::ORDER_CREATED_AT,
            'sort' => Criteria::ASC,
        ], [
            'language_reference' => UserFixture::DATA['user-john']['language_reference'],
            'total_count' => 4,
            'stories_expected' => [
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-second-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-second-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededLogginFrench(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        $this->checkSucceeded([
            'order' => StorySearchQuery::ORDER_CREATED_AT,
            'sort' => Criteria::ASC,
        ], [
            'language_reference' => UserFixture::DATA['user-pinkstory']['language_reference'],
            'total_count' => 6,
            'stories_expected' => [
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-second-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-second-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-third',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-fourth',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch1(): void
    {
        $this->checkSucceeded([
            'order' => StorySearchQuery::ORDER_CREATED_AT,
            'sort' => Criteria::ASC,
            'limit' => 2,
            'offset' => 2,
        ], [
            'language_reference' => 'language-english',
            'total_count' => 4,
            'stories_expected' => [
                [
                    'reference' => 'story-second-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch2(): void
    {
        $this->checkSucceeded([
            'order' => StorySearchQuery::ORDER_CREATED_AT,
            'limit' => 2,
            'offset' => 2,
        ], [
            'language_reference' => 'language-english',
            'total_count' => 4,
            'stories_expected' => [
                [
                    'reference' => 'story-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch3(): void
    {
        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'total_count' => 4,
            'stories_expected' => [
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch4(): void
    {
        $this->checkSucceeded([
            'user_id' => UserFixture::DATA['user-leslie']['id'],
        ], [
            'language_reference' => 'language-english',
            'total_count' => 0,
            'stories_expected' => [],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch5(): void
    {
        $this->checkSucceeded([
            'type' => StorySearchQuery::TYPE_PARENT,
        ], [
            'language_reference' => 'language-english',
            'total_count' => 2,
            'stories_expected' => [
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch6(): void
    {
        $this->checkSucceeded([
            'type' => StorySearchQuery::TYPE_CHILD,
        ], [
            'language_reference' => 'language-english',
            'total_count' => 2,
            'stories_expected' => [
                [
                    'reference' => 'story-second-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
                [
                    'reference' => 'story-second-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch7(): void
    {
        $this->checkSucceeded([
            'user_id' => UserFixture::DATA['user-john']['id'],
            'type' => StorySearchQuery::TYPE_CHILD,
            'limit' => 1,
        ], [
            'language_reference' => 'language-english',
            'total_count' => 2,
            'stories_expected' => [
                [
                    'reference' => 'story-second-second',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch8(): void
    {
        $this->checkSucceeded([
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-office']['id'],
                StoryThemeFixture::DATA['story-theme-threesome']['id'],
            ],
        ], [
            'language_reference' => 'language-english',
            'total_count' => 1,
            'stories_expected' => [
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => false,
                        'user_editable' => false,
                    ],
                ],
            ],
        ]);
    }

    public function testSucceededLogginFrenchSearch9(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        $this->checkSucceeded([
            'story_theme_ids' => [
                StoryThemeFixture::DATA['story-theme-office']['id'],
                StoryThemeFixture::DATA['story-theme-threesome']['id'],
            ],
        ], [
            'language_reference' => 'language-french',
            'total_count' => 2,
            'stories_expected' => [
                [
                    'reference' => 'story-first',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
                [
                    'reference' => 'story-third',
                    'options' => [
                        'user_image_defined' => false,
                        'editable' => true,
                        'user_editable' => true,
                    ],
                ],
            ],
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertEquals($options['total_count'], $responseData['stories_total']);
        $this->assertCount(count($options['stories_expected']), $responseData['stories']);

        foreach ($responseData['stories'] as $i => $storyData) {
            $this->checkProcessHasBeenSucceededTreatment($responseData, $options, $options['stories_expected'][$i]['reference'], $options['stories_expected'][$i]['options'], $storyData);
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }

    private function checkProcessHasBeenSucceededTreatment(array $responseData = [], array $options = [], string $storyFixtureReference, array $storyFixtureOptions, array $storyData)
    {
        // story informations
        $this->assertEquals(StoryFixture::DATA[$storyFixtureReference]['id'], $storyData['id']);
        $this->assertEquals(StoryFixture::DATA[$storyFixtureReference]['title'], $storyData['title']);
        $this->assertEquals((new AsciiSlugger())->slug(StoryFixture::DATA[$storyFixtureReference]['title'])->lower()->toString(), $storyData['title_slug']);
        $this->assertEquals(StoryFixture::DATA[$storyFixtureReference]['extract'], $storyData['extract']);
        $this->assertLessThan(new \DateTime(), new \DateTime($storyData['created_at']));
        $this->assertEquals($storyFixtureOptions['editable'], $storyData['editable']);

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
        $this->assertEquals($storyFixtureOptions['user_image_defined'], is_string($storyData['user']['image_url']));
        $this->assertLessThan(new \DateTime(), new \DateTime($storyData['user']['created_at']));
        $this->assertEquals($storyFixtureOptions['user_editable'], $storyData['user']['editable']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['language_reference']]['id'], $storyData['user']['language']['id']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['language_reference']]['title'], $storyData['user']['language']['title']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['user_reference']]['language_reference']]['locale'], $storyData['user']['language']['locale']);
        $this->assertIsString($storyData['user']['language']['image_url']);

        // language informations
        $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['language_reference']]['id'], $storyData['language']['id']);
        $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['language_reference']]['title'], $storyData['language']['title']);
        $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['language_reference']]['locale'], $storyData['language']['locale']);
        $this->assertIsString($storyData['language']['image_url']);

        // story image informations
        if (false === empty(StoryFixture::DATA[$storyFixtureReference]['story_image_reference'])) {
            $this->assertTrue(Uuid::isValid($storyData['story_image']['id']));
            $this->assertEquals(StoryImageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_image_reference']]['id'], $storyData['story_image']['id']);
            $this->assertEquals(StoryImageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_image_reference']]['translations'][$options['language_reference']]['title'], $storyData['story_image']['title']);
            $this->assertEquals((new AsciiSlugger())->slug(StoryImageFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_image_reference']]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyData['story_image']['title_slug']);
            $this->assertIsString($storyData['story_image']['image_url']);
        } else {
            $this->assertNull($storyData['story_image']);
        }

        // story themes informations
        $this->assertCount(count(StoryFixture::DATA[$storyFixtureReference]['story_themes_reference']), $storyData['story_themes']);

        foreach ($storyData['story_themes'] as $j => $storyThemeData) {
            $this->assertEquals(StoryThemeFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_themes_reference'][$j]]['id'], $storyThemeData['id']);
            $this->assertEquals(StoryThemeFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_themes_reference'][$j]]['translations'][$options['language_reference']]['title'], $storyThemeData['title']);
            $this->assertEquals((new AsciiSlugger())->slug(StoryThemeFixture::DATA[StoryFixture::DATA[$storyFixtureReference]['story_themes_reference'][$j]]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyThemeData['title_slug']);
        }

        // story parent/children informations
        if (false === empty(StoryFixture::DATA[$storyFixtureReference]['parent_reference'])) {
            $this->checkProcessHasBeenSucceededTreatment($responseData, $options, StoryFixture::DATA[$storyFixtureReference]['parent_reference'], $storyFixtureOptions, $storyData['parent']);
        } elseif (false === empty(StoryFixture::DATA[$storyFixtureReference]['children_reference'])) {
            $this->assertEquals(count(StoryFixture::DATA[$storyFixtureReference]['children_reference']), $storyData['children_total']);
        } else {
            $this->assertEquals(0, $storyData['children_total']);
        }
    }
}
