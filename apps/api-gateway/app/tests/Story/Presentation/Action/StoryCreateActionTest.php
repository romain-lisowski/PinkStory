<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
use App\Fixture\Story\StoryFixture;
use App\Fixture\Story\StoryImageFixture;
use App\Fixture\Story\StoryThemeFixture;
use App\Fixture\User\AccessTokenFixture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StoryCreateActionTest extends AbstractStoryActionTest
{
    private static array $storyData = [
        'title' => 'Première histoire test',
        'content' => 'Contenu de la première histoire test',
        'extract' => 'Extrait de la première histoire test',
        'language_id' => LanguageFixture::DATA['language-french']['id'],
        'parent_id' => StoryFixture::DATA['story-first']['id'],
        'story_image_id' => StoryImageFixture::DATA['story-image-second']['id'],
        'story_theme_ids' => [
            StoryThemeFixture::DATA['story-theme-orientation']['children']['story-theme-heterosexual']['id'],
            StoryThemeFixture::DATA['story-theme-place']['children']['story-theme-home']['id'],
            StoryThemeFixture::DATA['story-theme-number']['children']['story-theme-couple']['id'],
        ],
    ];

    private int $storyTotal;

    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_POST;
        self::$httpUri = '/story';
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        parent::setUp();

        // get story total
        $this->storyTotal = $this->storyRepository->count([]);
    }

    public function testSucceededWithoutImage(): void
    {
        $this->checkSucceeded([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'should_have_parent_defined' => false,
            'should_have_image_defined' => false,
        ]);
    }

    public function testSucceededWithImage(): void
    {
        $this->checkSucceeded([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'should_have_parent_defined' => false,
            'should_have_image_defined' => true,
        ]);
    }

    public function testSucceededWithParent(): void
    {
        $this->checkSucceeded([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'parent_id' => self::$storyData['parent_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'should_have_parent_defined' => true,
            'should_have_image_defined' => true,
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    public function testFailedMissingTitle(): void
    {
        $this->checkFailedMissingMandatory([
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedMissingContent(): void
    {
        $this->checkFailedMissingMandatory([
            'title' => self::$storyData['title'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedMissingExtract(): void
    {
        $this->checkFailedMissingMandatory([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedMissingLanguage(): void
    {
        $this->checkFailedMissingMandatory([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedWrongFormatLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => 'language_id',
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'language_id',
        ]);
    }

    public function testFailedNonExistentLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => Uuid::v4()->toRfc4122(),
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'language_id',
        ]);
    }

    public function testFailedWrongFormatStoryParent(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'parent_id' => 'parent_id',
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'parent_id',
        ]);
    }

    public function testFailedNonExistentStoryParent(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'parent_id' => Uuid::v4()->toRfc4122(),
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'parent_id',
        ]);
    }

    public function testFailedWithNoAuthorizationParent(): void
    {
        $this->checkFailedAccessDenied([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'parent_id' => StoryFixture::DATA['story-second']['id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedNonParentlessStoryParent(): void
    {
        // change user logged in (for authorization on parent story)
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'parent_id' => StoryFixture::DATA['story-second']['children']['story-second-first']['id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'parent_id',
        ]);
    }

    public function testFailedWrongFormatStoryImage(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => 'story_image_id',
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'story_image_id',
        ]);
    }

    public function testFailedNonExistentStoryImage(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => Uuid::v4()->toRfc4122(),
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'story_image_id',
        ]);
    }

    public function testFailedWrongFormatStoryTheme(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => array_merge(['story_theme_id'], self::$storyData['story_theme_ids']),
        ], [
            'story_theme_ids',
        ]);
    }

    public function testFailedNonExistentStoryTheme(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => array_merge([Uuid::v4()->toRfc4122()], self::$storyData['story_theme_ids']),
        ], [
            'story_theme_ids',
        ]);
    }

    public function testFailedNonChildlessStoryTheme(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => array_merge([StoryThemeFixture::DATA['story-theme-orientation']['id']], self::$storyData['story_theme_ids']),
        ], [
            'story_theme_ids',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertTrue(Uuid::isValid($responseData['story']['id']));

        // get fresh story from database
        $story = $this->storyRepository->findOne(Uuid::fromString($responseData['story']['id'])->toRfc4122());

        // check user has been created
        $this->assertTrue(Uuid::isValid($story->getId()));
        $this->assertEquals(self::$storyData['title'], $story->getTitle());
        $this->assertEquals((new AsciiSlugger())->slug(self::$storyData['title'])->lower()->toString(), $story->getTitleSlug());
        $this->assertEquals(self::$storyData['content'], $story->getContent());
        $this->assertEquals(self::$storyData['extract'], $story->getExtract());
        $this->assertEquals(self::$currentUser->getId(), $story->getUser()->getId());
        $this->assertEquals(self::$storyData['language_id'], $story->getLanguage()->getId());

        if (null !== $story->getParent()) {
            $this->assertEquals($options['should_have_parent_defined'], true);
            $this->assertEquals(self::$storyData['parent_id'], $story->getParent()->getId());
        } else {
            $this->assertEquals($options['should_have_parent_defined'], false);
        }

        if (null !== $story->getStoryImage()) {
            $this->assertEquals($options['should_have_image_defined'], true);
            $this->assertEquals(self::$storyData['story_image_id'], $story->getStoryImage()->getId());
        } else {
            $this->assertEquals($options['should_have_image_defined'], false);
        }

        $this->assertCount(count(self::$storyData['story_theme_ids']), $story->getStoryHasStoryThemes());

        foreach (self::$storyData['story_theme_ids'] as $storyThemeId) {
            $exists = false;

            foreach ($story->getStoryHasStoryThemes() as $storyHasStoryTheme) {
                if ($storyThemeId === $storyHasStoryTheme->getStoryTheme()->getId()) {
                    $exists = true;

                    break;
                }
            }

            if (false === $exists) {
                $this->fail('Story theme does not exist.');
            }
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals($story->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($story->getTitle(), $this->asyncTransport->get()[0]->getMessage()->getTitle());
        $this->assertEquals($story->getTitleSlug(), $this->asyncTransport->get()[0]->getMessage()->getTitleSlug());
        $this->assertEquals($story->getContent(), $this->asyncTransport->get()[0]->getMessage()->getContent());
        $this->assertEquals($story->getExtract(), $this->asyncTransport->get()[0]->getMessage()->getExtract());
        $this->assertEquals($story->getUser()->getId(), $this->asyncTransport->get()[0]->getMessage()->getUserId());
        $this->assertEquals($story->getLanguage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getLanguageId());

        if (null !== $story->getParent()) {
            $this->assertEquals($story->getParent()->getId(), $this->asyncTransport->get()[0]->getMessage()->getParentId());
        } else {
            $this->assertNull($this->asyncTransport->get()[0]->getMessage()->getParentId());
        }

        if (null !== $story->getStoryImage()) {
            $this->assertEquals($story->getStoryImage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getStoryImageId());
        } else {
            $this->assertNull($this->asyncTransport->get()[0]->getMessage()->getStoryImageId());
        }

        $this->assertCount(count(self::$storyData['story_theme_ids']), $this->asyncTransport->get()[0]->getMessage()->getStoryThemeIds());

        foreach ($this->asyncTransport->get()[0]->getMessage()->getStoryThemeIds() as $storyThemeId) {
            $this->assertTrue(in_array($storyThemeId, self::$storyData['story_theme_ids']));
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // get story total
        $storyTotal = $this->storyRepository->count([]);

        if ($storyTotal !== $this->storyTotal) {
            $this->fail();
        }

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
