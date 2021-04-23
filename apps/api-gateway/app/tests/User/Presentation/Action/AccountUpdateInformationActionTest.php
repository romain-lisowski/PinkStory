<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

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
        ]);
    }

    public function testFailedNonExistentGender(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => 'gender',
            'name' => self::$userData['name'],
            'language_id' => self::$userData['language_id'],
        ], [
            'gender',
        ]);
    }

    public function testFailedMissingName(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'language_id' => self::$userData['language_id'],
        ]);
    }

    public function testFailedMissingLanguage(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
        ]);
    }

    public function testFailedWrongFormatLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'language_id' => 'language_id',
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
        ], [
            'language_id',
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

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$user->getName(), $this->asyncTransport->get()[0]->getMessage()->getName());
        $this->assertEquals(self::$user->getGender(), $this->asyncTransport->get()[0]->getMessage()->getGender());
        $this->assertEquals(self::$user->getLanguage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getLanguageId());
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
