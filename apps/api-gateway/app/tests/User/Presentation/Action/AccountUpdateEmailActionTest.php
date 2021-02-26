<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserUpdatedEmailEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateEmailActionTest extends AbastractUserActionTest
{
    protected static string $httpMethod = Request::METHOD_PATCH;
    protected static string $httpUri = '/account/update-email';

    private static array $userData = [
        'email' => 'test@pinkstory.io',
    ];

    private string $userEmailValidationCode;

    protected function setUp(): void
    {
        parent::setUp();

        // get user email validation code
        $user = $this->userRepository->findOne(self::$pinkstoryUserData['id']);
        $this->userEmailValidationCode = $user->getEmailValidationCode();
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

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::$pinkstoryUserData['id']);
        $this->entityManager->refresh($user);

        // check user has been updated
        $this->assertEquals(self::$userData['email'], $user->getEmail());
        $this->assertFalse($user->isEmailValidated());
        $this->assertNotEquals($this->userEmailValidationCode, $user->getEmailValidationCode());
        $this->assertFalse($user->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserUpdatedEmailEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals($user->getEmailValidationCode(), $this->asyncTransport->get()[0]->getMessage()->getEmailValidationCode());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::$pinkstoryUserData['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertEquals(self::$pinkstoryUserData['email'], $user->getEmail());
        $this->assertTrue($user->isEmailValidated());
        $this->assertEquals($this->userEmailValidationCode, $user->getEmailValidationCode());
        $this->assertTrue($user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
