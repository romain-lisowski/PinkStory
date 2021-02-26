<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserValidatedEmailEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountValidateEmailActionTest extends AbastractUserActionTest
{
    protected const HTTP_METHOD = Request::METHOD_PATCH;
    protected const HTTP_URI = '/account/validate-email';

    private string $userEmailValidationCode;

    protected function setUp(): void
    {
        parent::setUp();

        // invalidate user email
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $user->regenerateEmailValidationCode();
        $this->userRepository->flush();
        $this->userEmailValidationCode = $user->getEmailValidationCode();
    }

    public function testSuccess(): void
    {
        $this->checkSuccess([
            'code' => $this->userEmailValidationCode,
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'code' => $this->userEmailValidationCode,
        ]);
    }

    public function testFailedMissingCode(): void
    {
        $this->checkFailedMissingMandatory();
    }

    public function testFailedWrongCodeFormat(): void
    {
        $this->checkFailedValidationFailed([
            'code' => 'code',
        ], [
            'code',
        ]);
    }

    public function testFailedWrongCode(): void
    {
        $this->checkFailedValidationFailed([
            'code' => '012345',
        ], [
            'code',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has been updated
        $this->assertTrue($user->isEmailValidated());
        $this->assertTrue($user->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserValidatedEmailEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertFalse($user->isEmailValidated());
        $this->assertFalse($user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
