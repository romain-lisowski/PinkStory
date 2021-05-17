<?php

declare(strict_types=1);

namespace App\Test\Story\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
use App\Fixture\Story\StoryFixture;
use App\Fixture\Story\StoryImageFixture;
use App\Fixture\Story\StoryThemeFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Story\Domain\Model\StoryTheme;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class StoryUpdateActionTest extends AbstractStoryActionTest
{
    private static array $storyData = [
        'title' => 'Histoire modifiée',
        'content' => 'Contenu de l\'histoire modifiée',
        'extract' => 'Extrait de l\'histoire modifiée',
        'language_id' => LanguageFixture::DATA['language-english']['id'],
        'story_image_id' => StoryImageFixture::DATA['story-image-second']['id'],
        'story_theme_ids' => [
            StoryThemeFixture::DATA['story-theme-heterosexual']['id'],
            StoryThemeFixture::DATA['story-theme-home']['id'],
            StoryThemeFixture::DATA['story-theme-couple']['id'],
        ],
    ];

    private string $storyTitle;
    private string $storyTitleSlug;
    private string $storyContent;
    private string $storyExtract;
    private string $storyUserId;
    private string $storyLanguageId;
    private ?string $storyParentId;
    private ?string $storyImageId;
    private array $storyThemeIds;

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/story/'.StoryFixture::DATA['story-first']['id'];
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        // get story data
        $this->populateStoryInformation(Uuid::fromString(StoryFixture::DATA['story-first']['id'])->toRfc4122());
    }

    public function testSucceededWithoutImageSameUserLoggedIn(): void
    {
        $this->checkSucceeded([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'should_have_image_defined' => false,
        ]);
    }

    public function testSucceededWithoutImageDifferentUserLoggedInButAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'should_have_image_defined' => false,
        ]);
    }

    public function testSucceededWithoutImageDifferentUserLoggedInButModerator(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSucceeded([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
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
            'should_have_image_defined' => true,
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedNotFoundWrongFormatId(): void
    {
        // wrong uri format
        self::$httpUri = '/story/id';

        $this->checkFailedNotFound([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedNotFoundNonExistentId(): void
    {
        // non existent id
        self::$httpUri = '/story/'.Uuid::v4()->toRfc4122();

        $this->checkFailedNotFound([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedDifferentUserLoggedIn(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-juliette']['id'];

        $this->checkFailedAccessDenied([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ]);
    }

    public function testFailedMissingTitle(): void
    {
        $this->checkFailedValidationFailed([
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'title',
        ]);
    }

    public function testFailedMissingContent(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'extract' => self::$storyData['extract'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'content',
        ]);
    }

    public function testFailedMissingExtract(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'language_id' => self::$storyData['language_id'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'extract',
        ]);
    }

    public function testFailedMissingLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'title' => self::$storyData['title'],
            'content' => self::$storyData['content'],
            'extract' => self::$storyData['extract'],
            'story_image_id' => self::$storyData['story_image_id'],
            'story_theme_ids' => self::$storyData['story_theme_ids'],
        ], [
            'language_id',
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
        $this->assertEquals([], $responseData);

        // get fresh story from database
        $story = $this->storyRepository->findOne(Uuid::fromString(StoryFixture::DATA['story-first']['id'])->toRfc4122());
        $this->entityManager->refresh($story);

        // check story has been updated
        $this->assertEquals(self::$storyData['title'], $story->getTitle());
        $this->assertEquals((new AsciiSlugger())->slug(self::$storyData['title'])->lower()->toString(), $story->getTitleSlug());
        $this->assertEquals(self::$storyData['content'], $story->getContent());
        $this->assertEquals(self::$storyData['extract'], $story->getExtract());
        $this->assertEquals($this->storyUserId, $story->getUser()->getId()); // can't be updated
        $this->assertEquals(self::$storyData['language_id'], $story->getLanguage()->getId());

        if (null !== $story->getParent()) {
            $this->assertEquals($this->storyParentId, $story->getParent()->getId()); // can't be updated
        } else {
            $this->assertNull($this->storyParentId);
        }

        if (null !== $story->getStoryImage()) {
            $this->assertEquals($options['should_have_image_defined'], true);
            $this->assertEquals(self::$storyData['story_image_id'], $story->getStoryImage()->getId());
        } else {
            $this->assertEquals($options['should_have_image_defined'], false);
        }

        $this->assertCount(count(self::$storyData['story_theme_ids']), $story->getStoryThemes());
        foreach ($story->getStoryThemes() as $storyTheme) {
            $this->assertTrue(in_array($storyTheme->getId(), self::$storyData['story_theme_ids']));
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals($story->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($story->getTitle(), $this->asyncTransport->get()[0]->getMessage()->getTitle());
        $this->assertEquals($story->getTitleSlug(), $this->asyncTransport->get()[0]->getMessage()->getTitleSlug());
        $this->assertEquals($story->getContent(), $this->asyncTransport->get()[0]->getMessage()->getContent());
        $this->assertEquals($story->getExtract(), $this->asyncTransport->get()[0]->getMessage()->getExtract());
        $this->assertEquals($story->getLanguage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getLanguageId());

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
        $storyId = false === empty($options['story_id']) ? $options['story_id'] : StoryFixture::DATA['story-first']['id'];

        // get fresh story from database
        $story = $this->storyRepository->findOne(Uuid::fromString($storyId)->toRfc4122());
        $this->entityManager->refresh($story);

        // check story has not been updated
        $this->assertEquals($this->storyTitle, $story->getTitle());
        $this->assertEquals($this->storyTitleSlug, $story->getTitleSlug());
        $this->assertEquals($this->storyContent, $story->getContent());
        $this->assertEquals($this->storyExtract, $story->getExtract());
        $this->assertEquals($this->storyUserId, $story->getUser()->getId());
        $this->assertEquals($this->storyLanguageId, $story->getLanguage()->getId());

        if (null !== $story->getParent()) {
            $this->assertEquals($this->storyParentId, $story->getParent()->getId());
        } else {
            $this->assertNull($this->storyParentId);
        }

        if (null !== $story->getStoryImage()) {
            $this->assertEquals($this->storyImageId, $story->getStoryImage()->getId());
        } else {
            $this->assertNull($this->storyImageId);
        }

        $this->assertCount(count($this->storyThemeIds), $story->getStoryThemes());
        foreach ($story->getStoryThemes() as $storyTheme) {
            $this->assertTrue(in_array($storyTheme->getId(), $this->storyThemeIds));
        }

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    private function populateStoryInformation(string $id)
    {
        // get story data
        $story = $this->storyRepository->findOne($id);

        $this->storyTitle = $story->getTitle();
        $this->storyTitleSlug = $story->getTitleSlug();
        $this->storyContent = $story->getContent();
        $this->storyExtract = $story->getExtract();
        $this->storyUserId = $story->getUser()->getId();
        $this->storyLanguageId = $story->getLanguage()->getId();
        $this->storyParentId = null !== $story->getParent() ? $story->getParent()->getId() : null;
        $this->storyImageId = null !== $story->getStoryImage() ? $story->getStoryImage()->getId() : null;
        $this->storyThemeIds = StoryTheme::extractIds($story->getStoryThemes()->toArray());
    }
}
