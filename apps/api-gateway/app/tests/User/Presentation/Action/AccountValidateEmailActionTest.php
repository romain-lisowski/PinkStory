<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserValidatedEmailEvent;

/**
 * @internal
 * @coversNothing
 */
final class AccountValidateEmailActionTest extends AbastractUserActionTest
{
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
        $this->client->request('PATCH', '/account/validate-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'email_validation_code' => $this->userEmailValidationCode,
        ]));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals([], $responseContent);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check email has been validated
        $this->assertTrue($user->isEmailValidated());
        $this->assertTrue($user->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserValidatedEmailEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
    }

    public function testFailedUnauthorized(): void
    {
        $this->client->request('PATCH', '/account/validate-email');

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseContent['exception']['type']);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check email has not been validated
        $this->assertFalse($user->isEmailValidated());
        $this->assertFalse($user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedWrongCodeFormat(): void
    {
        $this->client->request('PATCH', '/account/validate-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'email_validation_code' => 'code',
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('validation_failed_exception', $responseContent['exception']['type']);
        $this->assertEquals('email_validation_code', $responseContent['exception']['violations'][0]['property_path']);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check email has not been validated
        $this->assertFalse($user->isEmailValidated());
        $this->assertFalse($user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedWrongCode(): void
    {
        $this->client->request('PATCH', '/account/validate-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'email_validation_code' => '012345',
        ]));

        // check http response
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('access_denied_exception', $responseContent['exception']['type']);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check email has not been validated
        $this->assertFalse($user->isEmailValidated());
        $this->assertFalse($user->isEmailValidationCodeUsed());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
