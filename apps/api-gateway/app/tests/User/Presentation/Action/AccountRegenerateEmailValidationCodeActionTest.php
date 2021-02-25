<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserRegenerateEmailValidationCodeEvent;

/**
 * @internal
 * @coversNothing
 */
final class AccountRegenerateEmailValidationCodeActionTest extends AbastractUserActionTest
{
    public function testSuccess(): void
    {
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $userEmailValidationCode = $user->getEmailValidationCode();

        $this->client->request('PATCH', '/account/regenerate-email-validation-code', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ]);

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals([], $responseContent);

        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check email has been invalidated
        $this->assertFalse($user->isEmailValidated());
        $this->assertNotEquals($userEmailValidationCode, $user->getEmailValidationCode());
        $this->assertFalse($user->isEmailValidationCodeUsed());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserRegenerateEmailValidationCodeEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals($user->getEmailValidationCode(), $this->asyncTransport->get()[0]->getMessage()->getEmailValidationCode());
    }

    public function testFailedUnauthorized(): void
    {
        $this->client->request('PATCH', '/account/regenerate-email-validation-code');

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseContent['exception']['type']);

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
