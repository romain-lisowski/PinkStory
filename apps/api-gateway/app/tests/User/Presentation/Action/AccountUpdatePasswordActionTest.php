<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserUpdatedPasswordEvent;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdatePasswordActionTest extends AbastractUserActionTest
{
    protected static string $httpMethod = Request::METHOD_PATCH;
    protected static string $httpUri = '/account/update-password';

    private static array $userData = [
        'password' => '@Password3!',
    ];

    private string $userPassword;

    protected function setUp(): void
    {
        parent::setUp();

        // get user data
        $this->userPassword = self::$user->getPassword();
    }

    public function testSuccess(): void
    {
        $this->checkSuccess([
            'password' => self::$userData['password'],
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'password' => self::$userData['password'],
        ]);
    }

    public function testFailedMissingPassword(): void
    {
        $this->checkFailedMissingMandatory();
    }

    public function testFailedPasswordStrenght(): void
    {
        $this->checkFailedValidationFailed([
            'password' => 'password',
        ], [
            'password',
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
        $this->assertInstanceOf(UserUpdatedPasswordEvent::class, $this->asyncTransport->get()[0]->getMessage());
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
