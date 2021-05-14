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

/**
 * @internal
 * @coversNothing
 */
final class StorySearchActionTest extends AbstractStoryThemeActionTest
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
            'story_expected_reference' => [
                'story-first',
                'story-second',
                'story-second-first',
                'story-second-second',
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
            'story_expected_reference' => [
                'story-third',
                'story-fourth',
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
            'story_expected_reference' => [
                'story-first',
                'story-second',
                'story-second-first',
                'story-second-second',
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
            'story_expected_reference' => [
                'story-first',
                'story-second',
                'story-second-first',
                'story-second-second',
                'story-third',
                'story-fourth',
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
            'story_expected_reference' => [
                'story-second-first',
                'story-second-second',
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
            'story_expected_reference' => [
                'story-second',
                'story-first',
            ],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch3(): void
    {
        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'total_count' => 4,
            'story_expected_reference' => [
                'story-first',
                'story-second-second',
                'story-second-first',
                'story-second',
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
            'story_expected_reference' => [],
        ]);
    }

    public function testSucceededNoLogginButEnglishSearch5(): void
    {
        $this->checkSucceeded([
            'type' => StorySearchQuery::TYPE_PARENT,
        ], [
            'language_reference' => 'language-english',
            'total_count' => 2,
            'story_expected_reference' => [
                'story-first',
                'story-second',
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
            'story_expected_reference' => [
                'story-second-second',
                'story-second-first',
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
            'story_expected_reference' => [
                'story-second-second',
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
            'story_expected_reference' => [
                'story-first',
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
            'story_expected_reference' => [
                'story-first',
                'story-third',
            ],
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertEquals($options['total_count'], $responseData['stories_total']);
        $this->assertCount(count($options['story_expected_reference']), $responseData['stories']);

        foreach ($responseData['stories'] as $i => $storyData) {
            // story informations
            $this->assertEquals(StoryFixture::DATA[$options['story_expected_reference'][$i]]['id'], $storyData['id']);
            $this->assertEquals(StoryFixture::DATA[$options['story_expected_reference'][$i]]['title'], $storyData['title']);
            $this->assertEquals((new AsciiSlugger())->slug(StoryFixture::DATA[$options['story_expected_reference'][$i]]['title'])->lower()->toString(), $storyData['title_slug']);
            $this->assertEquals(StoryFixture::DATA[$options['story_expected_reference'][$i]]['extract'], $storyData['extract']);
            $this->assertLessThan(new \DateTime(), new \DateTime($storyData['created_at']));

            // story parent/children informations
            if (false === empty(StoryFixture::DATA[$options['story_expected_reference'][$i]]['parent_reference'])) {
            } elseif (false === empty(StoryFixture::DATA[$options['story_expected_reference'][$i]]['children_reference'])) {
                $this->assertEquals(count(StoryFixture::DATA[$options['story_expected_reference'][$i]]['children_reference']), $storyData['children_total']);
            } else {
                $this->assertEquals(0, $storyData['children_total']);
            }

            // story rating informations
            if (false === empty(StoryRatingFixture::DATA[$options['story_expected_reference'][$i]])) {
                $this->assertEquals(count(StoryRatingFixture::DATA[$options['story_expected_reference'][$i]]), $storyData['story_ratings_total']);
                $this->assertEquals($this->calculRate(StoryRatingFixture::DATA[$options['story_expected_reference'][$i]]), $storyData['rate']);
            } else {
                $this->assertEquals(0, $storyData['story_ratings_total']);
                $this->assertNull($storyData['rate']);
            }

            // user informations
            $this->assertEquals(UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['id'], $storyData['user']['id']);
            $this->assertEquals(UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['gender'], $storyData['user']['gender']);
            $this->assertEquals(self::$container->get(TranslatorInterface::class)->trans(strtolower(UserGender::getTranslationPrefix().UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['gender']), [], null, LanguageFixture::DATA[$options['language_reference']]['locale']), $storyData['user']['gender_reading']);
            $this->assertEquals(UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['name'], $storyData['user']['name']);
            $this->assertEquals((new AsciiSlugger())->slug(UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['name'])->lower()->toString(), $storyData['user']['name_slug']);
            $this->assertFalse($storyData['user']['image_defined']);
            $this->assertLessThan(new \DateTime(), new \DateTime($storyData['user']['created_at']));
            $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['language_reference']]['id'], $storyData['user']['language']['id']);
            $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['language_reference']]['title'], $storyData['user']['language']['title']);
            $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['user_reference']]['language_reference']]['locale'], $storyData['user']['language']['locale']);

            // language informations
            $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['language_reference']]['id'], $storyData['language']['id']);
            $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['language_reference']]['title'], $storyData['language']['title']);
            $this->assertEquals(LanguageFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['language_reference']]['locale'], $storyData['language']['locale']);

            // story image informations
            if (false === empty(StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_image_reference'])) {
                $this->assertEquals(StoryImageFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_image_reference']]['id'], $storyData['story_image']['id']);
                $this->assertEquals(StoryImageFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_image_reference']]['translations'][$options['language_reference']]['title'], $storyData['story_image']['title']);
                $this->assertEquals((new AsciiSlugger())->slug(StoryImageFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_image_reference']]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyData['story_image']['title_slug']);
            } else {
                $this->assertNull($storyData['story_image']);
            }

            // story themes informations
            $this->assertCount(count(StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_themes_reference']), $storyData['story_themes']);

            foreach ($storyData['story_themes'] as $j => $storyThemeData) {
                $this->assertEquals(StoryThemeFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_themes_reference'][$j]]['id'], $storyThemeData['id']);
                $this->assertEquals(StoryThemeFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_themes_reference'][$j]]['translations'][$options['language_reference']]['title'], $storyThemeData['title']);
                $this->assertEquals((new AsciiSlugger())->slug(StoryThemeFixture::DATA[StoryFixture::DATA[$options['story_expected_reference'][$i]]['story_themes_reference'][$j]]['translations'][$options['language_reference']]['title'])->lower()->toString(), $storyThemeData['title_slug']);
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }

    private function calculRate($storyRatingFixtures): ?float
    {
        $rates = array_map(function (array $storyRatingFixture) {
            return $storyRatingFixture['rate'];
        }, $storyRatingFixtures);

        if (0 === count($rates)) {
            return null;
        }

        return round(array_sum($rates) / count($rates), 1);
    }
}
