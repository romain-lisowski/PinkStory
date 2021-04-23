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
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/update-email';

        // get user data
        $this->userEmail = self::$user->getEmail();
        $this->userEmailValidationCode = self::$user->getEmailValidationCode();
    }

    public function testSuccess(): void
    {
        $this->checkSuccess([
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
        $this->checkFailedMissingMandatory();
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
        $this->assertEquals(self::$userData['email'], self::$user->getEmail());
        $this->assertFalse(self::$user->isEmailValidated());
        $this->assertNotEquals($this->userEmailValidationCode, self::$user->getEmailValidationCode());
        $this->assertFalse(self::$user->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals(self::$user->getEmailValidationCode(), $this->asyncTransport->get()[0]->getMessage()->getEmailValidationCode());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertEquals($this->userEmail, self::$user->getEmail());
        $this->assertTrue(self::$user->isEmailValidated());
        $this->assertEquals($this->userEmailValidationCode, self::$user->getEmailValidationCode());
        $this->assertTrue(self::$user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
