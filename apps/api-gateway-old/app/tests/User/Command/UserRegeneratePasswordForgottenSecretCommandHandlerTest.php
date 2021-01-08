<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommand;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommandHandler;
use App\User\Entity\User;
use App\User\Message\UserRegeneratePasswordForgottenSecretMessage;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    private $bus;
    private $validator;
    private $userRepository;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
        ;

        $this->command = new UserRegeneratePasswordForgottenSecretCommand();
        $this->command->email = $this->user->getEmail();

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->bus = $this->prophet->prophesize(MessageBusInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserRegeneratePasswordForgottenSecretCommandHandler($this->entityManager->reveal(), $this->bus->reveal(), $this->validator->reveal(), $this->userRepository->reveal());
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

        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->bus->dispatch(Argument::type(UserRegeneratePasswordForgottenSecretMessage::class))->shouldBeCalledOnce()->willReturn(new Envelope(new UserRegeneratePasswordForgottenSecretMessage($this->user->getId())));

        $this->handler->handle($this->command);

        $this->assertNotEquals($this->user->getPasswordForgottenSecret(), $secret);
        $this->assertFalse($this->user->isPasswordForgottenSecretUsed());
        $this->assertNotEquals($this->user->getPasswordForgottenSecretCreatedAt(), $secretCreatedAt);
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailInvalidCommand(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->userRepository->findOneByEmail($this->command->email)->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserRegeneratePasswordForgottenSecretMessage::class))->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailWrongEmail(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserRegeneratePasswordForgottenSecretMessage::class))->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }
}
