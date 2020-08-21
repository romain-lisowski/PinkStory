<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserSignupCommand;
use App\User\Command\UserSignupCommandHandler;
use App\User\Entity\User;
use App\User\Validator\Constraints\PasswordStrenght;
use Doctrine\ORM\EntityManagerInterface;
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
final class UserSignupCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserSignupCommand $command;
    private UserSignupCommandHandler $handler;
    private $entityManager;
    private $passwordEncoder;
    private $validator;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->command = new UserSignupCommand();
        $this->command->name = 'Yannis';
        $this->command->email = 'auth@yannissgarra.com';
        $this->command->password = '@Password2!';

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->passwordEncoder = $this->prophet->prophesize(UserPasswordEncoderInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->handler = new UserSignupCommandHandler($this->entityManager->reveal(), $this->passwordEncoder->reveal(), $this->validator->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $this->validator->validate($this->command->password, new PasswordStrenght())->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn('encodedPassword');

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->entityManager->persist(Argument::type(User::class))->shouldBeCalledOnce();
        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->handler->handle($this->command);

        $this->expectNotToPerformAssertions();
    }

    public function testHandleFailPasswordStrenght(): void
    {
        $this->validator->validate($this->command->password, new PasswordStrenght())->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'password', null, null, null, null)]));

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldNotBeCalled();

        $this->validator->validate(Argument::type(User::class))->shouldNotBeCalled();

        $this->entityManager->persist(Argument::type(User::class))->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailValidateUser(): void
    {
        $this->validator->validate($this->command->password, new PasswordStrenght())->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn('encodedPassword');

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'email', null, null, null, null)]));

        $this->entityManager->persist(Argument::type(User::class))->shouldNotBeCalled();
        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }
}
