<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserRegeneratePasswordForgottenSecretEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountRegeneratePasswordForgottenSecretActionTest extends AbastractUserActionTest
{
    protected static string $httpMethod = Request::METHOD_PATCH;
    protected static string $httpUri = '/account/regenerate-password-forgotten-secret';

    private static array $userData = [
        'email' => 'hello@pinkstory.io',
    ];

    private string $userPasswordForgottenSecret;
    private \DateTime $userPasswordForgottenSecretCreatedAt;

    protected function setUp(): void
    {
        parent::setUp();

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

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check user has been updated
        $this->assertNotEquals($this->userPasswordForgottenSecret, self::$user->getPasswordForgottenSecret());
        $this->assertFalse(self::$user->isPasswordForgottenSecretUsed());
        $this->assertNotEquals($this->userPasswordForgottenSecretCreatedAt, self::$user->getPasswordForgottenSecretCreatedAt());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserRegeneratePasswordForgottenSecretEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals(self::$user->getPasswordForgottenSecret(), $this->asyncTransport->get()[0]->getMessage()->getPasswordForgottenSecret());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check user has not been updated
        $this->assertEquals($this->userPasswordForgottenSecret, self::$user->getPasswordForgottenSecret());
        $this->assertTrue(self::$user->isPasswordForgottenSecretUsed());
        $this->assertEquals($this->userPasswordForgottenSecretCreatedAt, self::$user->getPasswordForgottenSecretCreatedAt());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
