<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserRegenerateEmailValidationCodeEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountRegenerateEmailValidationCodeActionTest extends AbastractUserActionTest
{
    protected const HTTP_METHOD = Request::METHOD_PATCH;
    protected const HTTP_URI = '/account/regenerate-email-validation-code';

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
        $this->checkSuccess();
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check email has been invalidated
        $this->assertFalse($user->isEmailValidated());
        $this->assertNotEquals($this->userEmailValidationCode, $user->getEmailValidationCode());
        $this->assertFalse($user->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserRegenerateEmailValidationCodeEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals($user->getEmailValidationCode(), $this->asyncTransport->get()[0]->getMessage()->getEmailValidationCode());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check email has not been invalidated
        $this->assertTrue($user->isEmailValidated());
        $this->assertTrue($user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
