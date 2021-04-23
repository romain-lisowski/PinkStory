<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountValidateEmailActionTest extends AbstractUserActionTest
{
    private string $userEmailValidationCode;

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/validate-email';

        // invalidate user email
        self::$user->regenerateEmailValidationCode();
        $this->userRepository->flush();
        $this->userEmailValidationCode = self::$user->getEmailValidationCode();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
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

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertTrue(self::$user->isEmailValidated());
        $this->assertTrue(self::$user->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertFalse(self::$user->isEmailValidated());
        $this->assertFalse(self::$user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
