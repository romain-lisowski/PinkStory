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
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/regenerate-password-forgotten-secret';
        self::$httpAuthorization = null;

        // get user data
        $this->userPasswordForgottenSecret = self::$user->getPasswordForgottenSecret();
        $this->userPasswordForgottenSecretCreatedAt = self::$user->getPasswordForgottenSecretCreatedAt();
    }

    public function testSuccess(): void
    {
        $this->checkSuccess([
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
        $this->assertNotEquals($this->userPasswordForgottenSecret, self::$user->getPasswordForgottenSecret());
        $this->assertFalse(self::$user->isPasswordForgottenSecretUsed());
        $this->assertNotEquals($this->userPasswordForgottenSecretCreatedAt, self::$user->getPasswordForgottenSecretCreatedAt());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals(self::$user->getPasswordForgottenSecret(), $this->asyncTransport->get()[0]->getMessage()->getPasswordForgottenSecret());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertEquals($this->userPasswordForgottenSecret, self::$user->getPasswordForgottenSecret());
        $this->assertTrue(self::$user->isPasswordForgottenSecretUsed());
        $this->assertEquals($this->userPasswordForgottenSecretCreatedAt, self::$user->getPasswordForgottenSecretCreatedAt());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
