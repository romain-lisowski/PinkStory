<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserUpdateInformationCommand;
use App\User\Command\UserUpdateInformationCommandHandler;
use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 * @coversNothing
 */
final class UserUpdateInformationCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserUpdateInformationCommand $command;
    private UserUpdateInformationCommandHandler $handler;
    private User $user;
    private $entityManager;
    private $validator;
    private $userRepository;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
        ;

        $this->command = new UserUpdateInformationCommand();
        $this->command->id = $this->user->getId();
        $this->command->name = 'Yannis2';

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserUpdateInformationCommandHandler($this->entityManager->reveal(), $this->validator->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $lastUpdatedAt = $this->user->getLastUpdatedAt();
        $name = $this->user->getName();

        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->handler->handle($this->command);

        $this->assertNotEquals($this->user->getName(), $name);
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailInvalidCommand(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->userRepository->findOne($this->command->id)->shouldNotBeCalled();

        $this->validator->validate(Argument::type(User::class))->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailUserNotFound(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->validator->validate(Argument::type(User::class))->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailInvalidUser(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }
}
