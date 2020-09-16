<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\InvalidSSLKeyException;
use App\Exception\ValidatorException;
use App\User\Command\UserLoginCommand;
use App\User\Command\UserLoginCommandHandler;
use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\NoResultException;
use Firebase\JWT\JWT;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 * @coversNothing
 */
final class UserLoginCommandHandlerTest extends KernelTestCase
{
    private Prophet $prophet;
    private UserLoginCommand $command;
    private UserLoginCommandHandler $handler;
    private User $user;
    private $params;
    private $passwordEncoder;
    private $validator;
    private $userRepository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
        ;

        $this->command = new UserLoginCommand();
        $this->command->email = $this->user->getEmail();
        $this->command->password = '@Password2!';

        $this->params = $this->prophet->prophesize(ParameterBagInterface::class);

        $this->passwordEncoder = $this->prophet->prophesize(UserPasswordEncoderInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserLoginCommandHandler($this->params->reveal(), $this->passwordEncoder->reveal(), $this->validator->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn(true);

        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('app_name'));
        $this->params->get('app_host_front_web')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_host_front_web'));
        $this->params->get('jwt_private_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_private_key'));
        $this->params->get('jwt_pass_phrase')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_pass_phrase'));
        $this->params->get('jwt_algorithm')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_algorithm'));

        $token = $this->handler->handle($this->command);

        $payload = JWT::decode($token, file_get_contents(self::$container->getParameter('jwt_public_key')), [self::$container->getParameter('jwt_algorithm')]);

        $this->assertEquals($payload->user_id, $this->user->getId());
        $this->assertEquals($payload->user_secret, $this->user->getSecret());
        $this->assertEquals($payload->app_secret, self::$container->getParameter('app_secret'));
        $this->assertEquals($payload->iss, self::$container->getParameter('app_name'));
        $this->assertEquals($payload->sub, $this->user->getId());
        $this->assertEquals($payload->aud, self::$container->getParameter('app_name'));
        $this->assertEquals($payload->mercure->subscribe[0], 'https://'.self::$container->getParameter('app_host_front_web').'/users/'.$this->user->getId());
    }

    public function testHandleFailInvalidCommand(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->userRepository->findOneByEmail($this->command->email)->shouldNotBeCalled();

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldNotBeCalled();

        $this->params->get('app_secret')->shouldNotBeCalled();
        $this->params->get('app_name')->shouldNotBeCalled();
        $this->params->get('app_host_front_web')->shouldNotBeCalled();
        $this->params->get('jwt_private_key')->shouldNotBeCalled();
        $this->params->get('jwt_pass_phrase')->shouldNotBeCalled();
        $this->params->get('jwt_algorithm')->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $token = $this->handler->handle($this->command);
    }

    public function testHandleFailNoUserFound(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldNotBeCalled();

        $this->params->get('app_secret')->shouldNotBeCalled();
        $this->params->get('app_name')->shouldNotBeCalled();
        $this->params->get('app_host_front_web')->shouldNotBeCalled();
        $this->params->get('jwt_private_key')->shouldNotBeCalled();
        $this->params->get('jwt_pass_phrase')->shouldNotBeCalled();
        $this->params->get('jwt_algorithm')->shouldNotBeCalled();

        $this->expectException(BadCredentialsException::class);

        $token = $this->handler->handle($this->command);
    }

    public function testHandleFailPasswordCheck(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn(false);

        $this->params->get('app_secret')->shouldNotBeCalled();
        $this->params->get('app_name')->shouldNotBeCalled();
        $this->params->get('app_host_front_web')->shouldNotBeCalled();
        $this->params->get('jwt_private_key')->shouldNotBeCalled();
        $this->params->get('jwt_pass_phrase')->shouldNotBeCalled();
        $this->params->get('jwt_algorithm')->shouldNotBeCalled();

        $this->expectException(BadCredentialsException::class);

        $token = $this->handler->handle($this->command);
    }

    public function testHandleFailJWTEncode(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn(true);

        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('app_name'));
        $this->params->get('app_host_front_web')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_host_front_web'));
        $this->params->get('jwt_private_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_private_key'));
        $this->params->get('jwt_pass_phrase')->shouldBeCalledOnce()->willReturn('wrong_passphrase');
        $this->params->get('jwt_algorithm')->shouldNotBeCalled();

        $this->expectException(InvalidSSLKeyException::class);

        $token = $this->handler->handle($this->command);
    }
}
