<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

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
        'language_id' => 'f11a8fd7-2a35-4f8a-a485-ab24acf214c1',
        'reading_language_ids' => [
            '99e8cc58-db0d-4ffd-9186-5a3f8c9e94e1',
            '9854df32-4a08-4f10-93ed-ae72ce52748b',
            '47afc681-9a6d-4fef-812e-f9df9a869945',
        ],
    ];

    private string $userGender;
    private string $userName;
    private string $languageId;

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/update-information';

        // get user data
        $this->userGender = self::$user->getGender();
        $this->userName = self::$user->getName();
        $this->languageId = self::$user->getLanguage()->getId();
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
        $this->assertEquals(self::$userData['gender'], self::$user->getGender());
        $this->assertEquals(self::$userData['name'], self::$user->getName());
        $this->assertEquals(self::$userData['language_id'], self::$user->getLanguage()->getId());
        $this->assertCount(count(self::$userData['reading_language_ids']), self::$user->getUserHasReadingLanguages());
        foreach (self::$userData['reading_language_ids'] as $readingLanguageId) {
            $this->assertTrue(in_array($readingLanguageId, Language::extractIds(self::$user->getReadingLanguages()->toArray())));
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$user->getName(), $this->asyncTransport->get()[0]->getMessage()->getName());
        $this->assertEquals(self::$user->getGender(), $this->asyncTransport->get()[0]->getMessage()->getGender());
        $this->assertEquals(self::$user->getLanguage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getLanguageId());
        $this->assertCount(self::$user->getReadingLanguages()->count(), $this->asyncTransport->get()[0]->getMessage()->getReadingLanguageIds());
        foreach (Language::extractIds(self::$user->getReadingLanguages()->toArray()) as $readingLanguageId) {
            $this->assertTrue(in_array($readingLanguageId, $this->asyncTransport->get()[0]->getMessage()->getReadingLanguageIds()));
        }
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertEquals($this->userGender, self::$user->getGender());
        $this->assertEquals($this->userName, self::$user->getName());
        $this->assertEquals($this->languageId, self::$user->getLanguage()->getId());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
