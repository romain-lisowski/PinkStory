<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Security\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdatePasswordForgottenActionTest extends AbstractUserActionTest
{
    private static array $userData = [
        'password' => '@Password3!',
    ];

    private string $userPassword;

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/update-password-forgotten';
        self::$httpAuthorization = null;

        // get user data
        self::$user->regeneratePasswordForgottenSecret();
        $this->userRepository->flush();
        $this->userPassword = self::$user->getPassword();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
            'password' => self::$userData['password'],
            'secret' => self::$user->getPasswordForgottenSecret(),
        ]);
    }

    public function testFailedMissingPassword(): void
    {
        $this->checkFailedMissingMandatory([
            'secret' => self::$user->getPasswordForgottenSecret(),
        ]);
    }

    public function testFailedPasswordStrenght(): void
    {
        $this->checkFailedValidationFailed([
            'password' => 'password',
            'secret' => self::$user->getPasswordForgottenSecret(),
        ], [
            'password',
        ]);
    }

    public function testFailedMissingSecret(): void
    {
        $this->checkFailedMissingMandatory([
            'password' => self::$userData['password'],
        ]);
    }

    public function testFailedWrongFormatSecret(): void
    {
        $this->checkFailedValidationFailed([
            'password' => self::$userData['password'],
            'secret' => 'secret',
        ], [
            'secret',
        ]);
    }

    public function testFailedSecretNotFound(): void
    {
        $this->checkFailedValidationFailed([
            'password' => self::$userData['password'],
            'secret' => Uuid::v4()->toRfc4122(),
        ], [
            'secret',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertTrue(self::$container->get(UserPasswordEncoderInterface::class)->isPasswordValid(self::$user, self::$userData['password']));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$user->getPassword(), $this->asyncTransport->get()[0]->getMessage()->getPassword());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertEquals($this->userPassword, self::$user->getPassword());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
