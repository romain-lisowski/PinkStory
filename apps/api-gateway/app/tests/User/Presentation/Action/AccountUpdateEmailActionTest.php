<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserUpdatedEmailEvent;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateEmailActionTest extends AbastractUserActionTest
{
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
        $this->client->request('PATCH', '/account/update-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'email' => self::USER_DATA['email'],
        ]));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals([], $responseContent);

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

    public function testFailedUnauthorized(): void
    {
        $this->client->request('PATCH', '/account/update-email', [], [], [], json_encode([
            'email' => self::USER_DATA['email'],
        ]));

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseContent['exception']['type']);

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

    public function testFailedMissingEmail(): void
    {
        $this->client->request('PATCH', '/account/update-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ]);

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('request_body_param_missing_mandatory_exception', $responseContent['exception']['type']);

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

    public function testFailedWrongFormatEmail(): void
    {
        $this->client->request('PATCH', '/account/update-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'email' => 'email',
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('validation_failed_exception', $responseContent['exception']['type']);
        $this->assertEquals('email', $responseContent['exception']['violations'][0]['property_path']);

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

    public function testFailedNonExistentEmail(): void
    {
        $this->client->request('PATCH', '/account/update-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'email' => 'email@email.em',
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('validation_failed_exception', $responseContent['exception']['type']);
        $this->assertEquals('email', $responseContent['exception']['violations'][0]['property_path']);

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

    public function testFailedNonUniqueEmail(): void
    {
        $this->client->request('PATCH', '/account/update-email', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'email' => 'hello@yannissgarra.com',
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('validation_failed_exception', $responseContent['exception']['type']);
        $this->assertEquals('email', $responseContent['exception']['violations'][0]['property_path']);

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
