<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserUpdatedInformationEvent;
use App\User\Domain\Model\UserGender;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateInformationActionTest extends AbastractUserActionTest
{
    protected static string $httpMethod = Request::METHOD_PATCH;
    protected static string $httpUri = '/account/update-information';

    private static array $userData = [
        'name' => 'Test',
        'gender' => UserGender::MALE,
    ];

    public function testSuccess(): void
    {
        $this->checkSuccess([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
        ]);
    }

    public function testFailedMissingGender(): void
    {
        $this->checkFailedMissingMandatory([
            'name' => self::$userData['name'],
        ]);
    }

    public function testFailedNonExistentGender(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => 'gender',
            'name' => self::$userData['name'],
        ], [
            'gender',
        ]);
    }

    public function testFailedMissingName(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::$pinkstoryUserData['id']);
        $this->entityManager->refresh($user);

        // check user has been updated
        $this->assertEquals(self::$userData['name'], $user->getName());
        $this->assertEquals(self::$userData['gender'], $user->getGender());

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserUpdatedInformationEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getName(), $this->asyncTransport->get()[0]->getMessage()->getName());
        $this->assertEquals($user->getGender(), $this->asyncTransport->get()[0]->getMessage()->getGender());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::$pinkstoryUserData['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertEquals(self::$pinkstoryUserData['name'], $user->getName());
        $this->assertEquals(self::$pinkstoryUserData['gender'], $user->getGender());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
