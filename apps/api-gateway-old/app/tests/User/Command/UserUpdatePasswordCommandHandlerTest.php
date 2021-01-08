<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserUpdatePasswordCommand;
use App\User\Command\UserUpdatePasswordCommandHandler;
use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 * @coversNothing
 */
final class UserUpdatePasswordCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserUpdatePasswordCommand $command;
    private UserUpdatePasswordCommandHandler $handler;
    private User $user;
    private $entityManager;
    private $passwordEncoder;
    private $validator;
    private $userRepository;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
            ->updatePassword('password')
        ;

        $this->command = new UserUpdatePasswordCommand();
        $this->command->id = $this->user->getId();
        $this->command->oldPassword = 'password';
        $this->command->password = '@Password2!';

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->passwordEncoder = $this->prophet->prophesize(UserPasswordEncoderInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserUpdatePasswordCommandHandler($this->entityManager->reveal(), $this->passwordEncoder->reveal(), $this->validator->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $lastUpdatedAt = $this->user->getLastUpdatedAt();

        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn($this->command->password);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->handler->handle($this->command);

        $this->assertEquals($this->user->getPassword(), $this->command->password);
        $this->assertTrue($this->user->isPasswordForgottenSecretUsed());
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailInvalidCommand(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->userRepository->findOne($this->command->id)->shouldNotBeCalled();

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldNotBeCalled();

        $this->validator->validate(Argument::type(User::class))->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailUserNotFound(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldNotBeCalled();

        $this->validator->validate(Argument::type(User::class))->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailInvalidUser(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn($this->command->password);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }
}
