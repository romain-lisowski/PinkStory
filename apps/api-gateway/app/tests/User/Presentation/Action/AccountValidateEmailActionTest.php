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
        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/validate-email';

        parent::setUp();

        // invalidate user email
        self::$currentUser->regenerateEmailValidationCode();
        $this->userRepository->flush();
        $this->userEmailValidationCode = self::$currentUser->getEmailValidationCode();
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
        $this->assertTrue(self::$currentUser->isEmailValidated());
        $this->assertTrue(self::$currentUser->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$currentUser->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$currentUser->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertFalse(self::$currentUser->isEmailValidated());
        $this->assertFalse(self::$currentUser->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
