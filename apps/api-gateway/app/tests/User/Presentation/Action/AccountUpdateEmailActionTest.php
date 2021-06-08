<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateEmailActionTest extends AbstractUserActionTest
{
    private static array $userData = [
        'email' => 'test@pinkstory.io',
    ];

    private string $userEmail;
    private string $userEmailValidationCode;

    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/update-email';

        parent::setUp();

        // get user data
        $this->userEmail = self::$currentUser->getEmail();
        $this->userEmailValidationCode = self::$currentUser->getEmailValidationCode();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
            'email' => self::$userData['email'],
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'email' => self::$userData['email'],
        ]);
    }

    public function testFailedMissingEmail(): void
    {
        $this->checkFailedValidationFailed(null, [
            'email',
        ]);
    }

    public function testFailedWrongFormatEmail(): void
    {
        $this->checkFailedValidationFailed([
            'email' => 'email',
        ], [
            'email',
        ]);
    }

    public function testFailedNonExistentEmail(): void
    {
        $this->checkFailedValidationFailed([
            'email' => 'email@email.em',
        ], [
            'email',
        ]);
    }

    public function testFailedNonUniqueEmail(): void
    {
        $this->checkFailedValidationFailed([
            'email' => 'hello@yannissgarra.com',
        ], [
            'email',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertEquals(self::$userData['email'], self::$currentUser->getEmail());
        $this->assertFalse(self::$currentUser->isEmailValidated());
        $this->assertNotEquals($this->userEmailValidationCode, self::$currentUser->getEmailValidationCode());
        $this->assertFalse(self::$currentUser->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$currentUser->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$currentUser->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals(self::$currentUser->getEmailValidationCode(), $this->asyncTransport->get()[0]->getMessage()->getEmailValidationCode());
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // check user has not been updated
        $this->assertEquals($this->userEmail, self::$currentUser->getEmail());
        $this->assertTrue(self::$currentUser->isEmailValidated());
        $this->assertEquals($this->userEmailValidationCode, self::$currentUser->getEmailValidationCode());
        $this->assertTrue(self::$currentUser->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
