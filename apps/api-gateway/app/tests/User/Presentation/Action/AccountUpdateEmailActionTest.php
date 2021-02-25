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
    protected const HTTP_METHOD = Request::METHOD_PATCH;
    protected const HTTP_URI = '/account/update-email';

    private const USER_DATA = [
        'email' => 'test@pinkstory.io',
    ];

    private string $userEmailValidationCode;

    protected function setUp(): void
    {
        parent::setUp();

        // init user image
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->userEmailValidationCode = $user->getEmailValidationCode();
    }

    public function testSuccess(): void
    {
        $this->checkSuccess([
            'email' => self::USER_DATA['email'],
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'email' => self::USER_DATA['email'],
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
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check email has been updated
        $this->assertEquals(self::USER_DATA['email'], $user->getEmail());
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
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check email has not been updated
        $this->assertEquals(self::PINKSTORY_USER_DATA['email'], $user->getEmail());
        $this->assertTrue($user->isEmailValidated());
        $this->assertEquals($this->userEmailValidationCode, $user->getEmailValidationCode());
        $this->assertTrue($user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
