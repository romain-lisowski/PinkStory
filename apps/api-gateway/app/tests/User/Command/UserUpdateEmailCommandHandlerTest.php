<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserUpdateEmailCommand;
use App\User\Command\UserUpdateEmailCommandHandler;
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
final class UserUpdateEmailCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserUpdateEmailCommand $command;
    private UserUpdateEmailCommandHandler $handler;
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

        $this->command = new UserUpdateEmailCommand();
        $this->command->id = $this->user->getId();
        $this->command->email = 'auth+2@yannissgarra.com';

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserUpdateEmailCommandHandler($this->entityManager->reveal(), $this->validator->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $lastUpdatedAt = $this->user->getLastUpdatedAt();
        $email = $this->user->getEmail();
        $secret = $this->user->getEmailValidationSecret();

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->handler->handle($this->command);

        $this->assertNotEquals($this->user->getEmail(), $email);
        $this->assertFalse($this->user->isEmailValidated());
        $this->assertNotEquals($this->user->getEmailValidationSecret(), $secret);
        $this->assertFalse($this->user->isEmailValidationSecretUsed());
        $this->assertTrue($this->user->isPasswordForgottenSecretUsed());
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailWrongUser(): void
    {
        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->validator->validate(Argument::type(User::class))->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailValidateUser(): void
    {
        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'email', null, null, null, null)]));

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }
}
