<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\InvalidSSLKeyException;
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
    private $userRepository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->prophet = new Prophet();

        $this->command = new UserLoginCommand();
        $this->command->email = 'auth@yannissgarra.com';
        $this->command->password = '@Password2!';

        $this->user = (new User())
            ->setName('Yannis')
            ->setEmail('auth@yannissgarra.com')
        ;

        $this->params = $this->prophet->prophesize(ParameterBagInterface::class);

        $this->passwordEncoder = $this->prophet->prophesize(UserPasswordEncoderInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserLoginCommandHandler($this->params->reveal(), $this->passwordEncoder->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('app_name'));
        $this->params->get('jwt_private_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_private_key'));
        $this->params->get('jwt_pass_phrase')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_pass_phrase'));

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn(true);

        $token = $this->handler->handle($this->command);

        $payload = JWT::decode($token, file_get_contents(self::$container->getParameter('jwt_public_key')), ['RS256']);

        $this->assertEquals($payload->user_id, $this->user->getId());
        $this->assertEquals($payload->user_secret, $this->user->getSecret());
        $this->assertEquals($payload->app_secret, self::$container->getParameter('app_secret'));
        $this->assertEquals($payload->iss, self::$container->getParameter('app_name'));
        $this->assertEquals($payload->sub, $this->user->getEmail());
        $this->assertEquals($payload->aud, self::$container->getParameter('app_name'));
    }

    public function testHandleFailNoUserFound(): void
    {
        $this->params->get('app_secret')->shouldNotBeCalled();
        $this->params->get('app_name')->shouldNotBeCalled();
        $this->params->get('jwt_private_key')->shouldNotBeCalled();
        $this->params->get('jwt_pass_phrase')->shouldNotBeCalled();

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldNotBeCalled();

        $this->expectException(BadCredentialsException::class);

        $token = $this->handler->handle($this->command);
    }

    public function testHandleFailPasswordCheck(): void
    {
        $this->params->get('app_secret')->shouldNotBeCalled();
        $this->params->get('app_name')->shouldNotBeCalled();
        $this->params->get('jwt_private_key')->shouldNotBeCalled();
        $this->params->get('jwt_pass_phrase')->shouldNotBeCalled();

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn(false);

        $this->expectException(BadCredentialsException::class);

        $token = $this->handler->handle($this->command);
    }

    public function testHandleFailJWTEncode(): void
    {
        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('app_name'));
        $this->params->get('jwt_private_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_private_key'));
        $this->params->get('jwt_pass_phrase')->shouldBeCalledOnce()->willReturn('wrong_passphrase');

        $this->userRepository->findOneByEmail($this->command->email)->shouldBeCalledOnce()->willReturn($this->user);

        $this->passwordEncoder->isPasswordValid(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn(true);

        $this->expectException(InvalidSSLKeyException::class);

        $token = $this->handler->handle($this->command);
    }
}
