<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountRegeneratePasswordForgottenSecretActionTest extends AbstractUserActionTest
{
    private static array $userData = [
        'email' => 'hello@pinkstory.io',
    ];

    private string $userPasswordForgottenSecret;
    private \DateTime $userPasswordForgottenSecretCreatedAt;

    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/regenerate-password-forgotten-secret';
        self::$httpAuthorizationToken = null;

        parent::setUp();

        // get user data
        $this->userPasswordForgottenSecret = self::$defaultUser->getPasswordForgottenSecret();
        $this->userPasswordForgottenSecretCreatedAt = self::$defaultUser->getPasswordForgottenSecretCreatedAt();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
            'email' => self::$userData['email'],
        ]);
    }

    public function testFailedMissingEmail(): void
    {
        $this->checkFailedMissingMandatory();
    }

    public function testFailedEmailNotFound(): void
    {
        $this->checkFailedValidationFailed([
            'email' => 'test@pinkstory.io',
        ], [
            'email',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertNotEquals($this->userPasswordForgottenSecret, self::$defaultUser->getPasswordForgottenSecret());
        $this->assertFalse(self::$defaultUser->isPasswordForgottenSecretUsed());
        $this->assertNotEquals($this->userPasswordForgottenSecretCreatedAt, self::$defaultUser->getPasswordForgottenSecretCreatedAt());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$defaultUser->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$defaultUser->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals(self::$defaultUser->getPasswordForgottenSecret(), $this->asyncTransport->get()[0]->getMessage()->getPasswordForgottenSecret());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertEquals($this->userPasswordForgottenSecret, self::$defaultUser->getPasswordForgottenSecret());
        $this->assertTrue(self::$defaultUser->isPasswordForgottenSecretUsed());
        $this->assertEquals($this->userPasswordForgottenSecretCreatedAt, self::$defaultUser->getPasswordForgottenSecretCreatedAt());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
