<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\User\Command\UserRegeneratePasswordForgottenSecretCommand;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommandHandler;
use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

/**
 * @internal
 * @coversNothing
 */
final class UserRegeneratePasswordForgottenSecretCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserRegeneratePasswordForgottenSecretCommand $command;
    private UserRegeneratePasswordForgottenSecretCommandHandler $handler;
    private User $user;
    private $entityManager;
    private $userRepository;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->changeEmail('auth@yannissgarra.com')
        ;

        $this->command = new UserRegeneratePasswordForgottenSecretCommand();
        $this->command->email = $this->user->getEmail();

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserRegeneratePasswordForgottenSecretCommandHandler($this->entityManager->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $secret = $this->user->getPasswordForgottenSecret();
        $secretCreatedAt = $this->user->getPasswordForgottenSecretCreatedAt();
        $lastUpdatedAt = $this->user->getLastUpdatedAt();

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->handler->handle($this->command);

        $this->assertNotEquals($this->user->getPasswordForgottenSecret(), $secret);
        $this->assertFalse($this->user->isPasswordForgottenSecretUsed());
        $this->assertNotEquals($this->user->getPasswordForgottenSecretCreatedAt(), $secretCreatedAt);
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailWrongEmail(): void
    {
        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }
}
