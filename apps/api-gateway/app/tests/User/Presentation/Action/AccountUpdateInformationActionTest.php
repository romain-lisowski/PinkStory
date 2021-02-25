<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserUpdatedInformationEvent;
use App\User\Domain\Model\UserGender;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateInformationActionTest extends AbastractUserActionTest
{
    private const USER_DATA = [
        'name' => 'Test',
        'gender' => UserGender::MALE,
    ];

    public function testSuccess(): void
    {
        $this->client->request('PATCH', '/account/update-information', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
        ]));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals([], $responseContent);

        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has been updated
        $this->assertEquals(self::USER_DATA['name'], $user->getName());
        $this->assertEquals(self::USER_DATA['gender'], $user->getGender());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserUpdatedInformationEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getName(), $this->asyncTransport->get()[0]->getMessage()->getName());
        $this->assertEquals($user->getGender(), $this->asyncTransport->get()[0]->getMessage()->getGender());
    }

    public function testFailedUnauthorized(): void
    {
        $this->client->request('PATCH', '/account/update-information', [], [], [], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
        ]));

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseContent['exception']['type']);

        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertEquals(self::PINKSTORY_USER_DATA['name'], $user->getName());
        $this->assertEquals(self::PINKSTORY_USER_DATA['gender'], $user->getGender());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedMissingGender(): void
    {
        $this->client->request('PATCH', '/account/update-information', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'name' => self::USER_DATA['name'],
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('request_body_param_missing_mandatory_exception', $responseContent['exception']['type']);

        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertEquals(self::PINKSTORY_USER_DATA['name'], $user->getName());
        $this->assertEquals(self::PINKSTORY_USER_DATA['gender'], $user->getGender());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedNonExistentGender(): void
    {
        $this->client->request('PATCH', '/account/update-information', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'gender' => 'gender',
            'name' => self::USER_DATA['name'],
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('validation_failed_exception', $responseContent['exception']['type']);
        $this->assertEquals('gender', $responseContent['exception']['violations'][0]['property_path']);

        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertEquals(self::PINKSTORY_USER_DATA['name'], $user->getName());
        $this->assertEquals(self::PINKSTORY_USER_DATA['gender'], $user->getGender());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedMissingName(): void
    {
        $this->client->request('PATCH', '/account/update-information', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'gender' => self::USER_DATA['gender'],
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('request_body_param_missing_mandatory_exception', $responseContent['exception']['type']);

        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertEquals(self::PINKSTORY_USER_DATA['name'], $user->getName());
        $this->assertEquals(self::PINKSTORY_USER_DATA['gender'], $user->getGender());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
