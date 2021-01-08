<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserRegenerateEmailValidationSecretCommand;
use App\User\Command\UserRegenerateEmailValidationSecretCommandHandler;
use App\User\Entity\User;
use App\User\Message\UserRegenerateEmailValidationSecretMessage;
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
final class UserRegenerateEmailValidationSecretCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserRegenerateEmailValidationSecretCommand $command;
    private UserRegenerateEmailValidationSecretCommandHandler $handler;
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

        $this->command = new UserRegenerateEmailValidationSecretCommand();
        $this->command->id = $this->user->getId();

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->bus = $this->prophet->prophesize(MessageBusInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserRegenerateEmailValidationSecretCommandHandler($this->entityManager->reveal(), $this->bus->reveal(), $this->validator->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $secret = $this->user->getEmailValidationSecret();
        $lastUpdatedAt = $this->user->getLastUpdatedAt();

        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->bus->dispatch(Argument::type(UserRegenerateEmailValidationSecretMessage::class))->shouldBeCalledOnce()->willReturn(new Envelope(new UserRegenerateEmailValidationSecretMessage($this->user->getId())));

        $this->handler->handle($this->command);

        $this->assertFalse($this->user->isEmailValidated());
        $this->assertNotEquals($this->user->getEmailValidationSecret(), $secret);
        $this->assertFalse($this->user->isEmailValidationSecretUsed());
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailInvalidCommand(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->userRepository->findOne($this->command->id)->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserRegenerateEmailValidationSecretMessage::class))->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailWrongId(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserRegenerateEmailValidationSecretMessage::class))->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }
}
