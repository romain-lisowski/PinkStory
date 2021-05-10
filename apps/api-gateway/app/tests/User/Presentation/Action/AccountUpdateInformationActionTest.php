<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
use App\Language\Domain\Model\Language;
use App\User\Domain\Model\UserGender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateInformationActionTest extends AbstractUserActionTest
{
    private static array $userData = [
        'name' => 'Test',
        'gender' => UserGender::MALE,
        'language_id' => LanguageFixture::DATA['language-french']['id'],
        'reading_language_ids' => [
            LanguageFixture::DATA['language-spanish']['id'],
            LanguageFixture::DATA['language-french']['id'],
            LanguageFixture::DATA['language-italian']['id'],
        ],
    ];

    private string $userGender;
    private string $userName;
    private string $languageId;

    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/update-information';

        parent::setUp();

        // get user data
        $this->userGender = self::$currentUser->getGender();
        $this->userName = self::$currentUser->getName();
        $this->languageId = self::$currentUser->getLanguage()->getId();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
            'reading_language_ids' => self::$userData['reading_language_ids'],
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
        ]);
    }

    public function testFailedMissingGender(): void
    {
        $this->checkFailedMissingMandatory([
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
            'reading_language_ids' => self::$userData['reading_language_ids'],
        ]);
    }

    public function testFailedNonExistentGender(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => 'gender',
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
            'reading_language_ids' => self::$userData['reading_language_ids'],
        ], [
            'gender',
        ]);
    }

    public function testFailedMissingName(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'language_id' => self::$userData['language_id'],
            'reading_language_ids' => self::$userData['reading_language_ids'],
        ]);
    }

    public function testFailedMissingLanguage(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'reading_language_ids' => self::$userData['reading_language_ids'],
        ]);
    }

    public function testFailedWrongFormatLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => 'language_id',
            'reading_language_ids' => self::$userData['reading_language_ids'],
        ], [
            'language_id',
        ]);
    }

    public function testFailedNonExistentLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => Uuid::v4()->toRfc4122(),
            'reading_language_ids' => self::$userData['reading_language_ids'],
        ], [
            'language_id',
        ]);
    }

    public function testFailedMissingReadingLanguages(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
        ]);
    }

    public function testFailedWrongFormatReadingLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
            'reading_language_ids' => array_merge(
                self::$userData['reading_language_ids'],
                ['language_id']
            ),
        ], [
            'reading_language_ids',
        ]);
    }

    public function testFailedNonExistentReadingLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
            'reading_language_ids' => array_merge(
                self::$userData['reading_language_ids'],
                [Uuid::v4()->toRfc4122()]
            ),
        ], [
            'reading_language_ids',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertEquals(self::$userData['gender'], self::$currentUser->getGender());
        $this->assertEquals(self::$userData['name'], self::$currentUser->getName());
        $this->assertEquals(self::$userData['language_id'], self::$currentUser->getLanguage()->getId());
        $this->assertCount(count(self::$userData['reading_language_ids']), self::$currentUser->getUserHasReadingLanguages());

        foreach (self::$userData['reading_language_ids'] as $readingLanguageId) {
            $this->assertTrue(in_array($readingLanguageId, Language::extractIds(self::$currentUser->getReadingLanguages()->toArray())));
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$currentUser->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$currentUser->getName(), $this->asyncTransport->get()[0]->getMessage()->getName());
        $this->assertEquals(self::$currentUser->getGender(), $this->asyncTransport->get()[0]->getMessage()->getGender());
        $this->assertEquals(self::$currentUser->getLanguage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getLanguageId());
        $this->assertCount(self::$currentUser->getReadingLanguages()->count(), $this->asyncTransport->get()[0]->getMessage()->getReadingLanguageIds());
        foreach (Language::extractIds(self::$currentUser->getReadingLanguages()->toArray()) as $readingLanguageId) {
            $this->assertTrue(in_array($readingLanguageId, $this->asyncTransport->get()[0]->getMessage()->getReadingLanguageIds()));
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // check user has not been updated
        $this->assertEquals($this->userGender, self::$currentUser->getGender());
        $this->assertEquals($this->userName, self::$currentUser->getName());
        $this->assertEquals($this->languageId, self::$currentUser->getLanguage()->getId());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
