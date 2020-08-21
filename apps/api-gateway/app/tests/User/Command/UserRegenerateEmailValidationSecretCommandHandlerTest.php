<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\User\Command\UserRegenerateEmailValidationSecretCommand;
use App\User\Command\UserRegenerateEmailValidationSecretCommandHandler;
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
final class UserRegenerateEmailValidationSecretCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserRegenerateEmailValidationSecretCommand $command;
    private UserRegenerateEmailValidationSecretCommandHandler $handler;
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

        $this->command = new UserRegenerateEmailValidationSecretCommand();
        $this->command->id = $this->user->getId();

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserRegenerateEmailValidationSecretCommandHandler($this->entityManager->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $secret = $this->user->getEmailValidationSecret();
        $lastUpdatedAt = $this->user->getLastUpdatedAt();

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->handler->handle($this->command);

        $this->assertFalse($this->user->isEmailValidated());
        $this->assertNotEquals($this->user->getEmailValidationSecret(), $secret);
        $this->assertFalse($this->user->isEmailValidationSecretUsed());
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailWrongId(): void
    {
        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }
}
