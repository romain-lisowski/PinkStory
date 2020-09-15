<?php

declare(strict_types=1);

namespace App\Test\User\Security;

use App\User\Command\UserLoginCommand;
use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use App\User\Security\UserAuthenticator;
use DateTime;
use Firebase\JWT\JWT;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class UserAuthenticatorTest extends KernelTestCase
{
    private Prophet $prophet;
    private UserLoginCommand $command;
    private UserAuthenticator $authenticator;
    private User $user;
    private $params;
    private $userRepository;

    public function setUp(): void
    {
        self::bootKernel();

        $this->prophet = new Prophet();

        $this->command = new UserLoginCommand();
        $this->command->email = 'auth@yannissgarra.com';
        $this->command->password = '@Password2!';

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
        ;

        $this->params = $this->prophet->prophesize(ParameterBagInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->authenticator = new UserAuthenticator($this->params->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testSupportsSucess(): void
    {
        $request = new Request();
        $request->headers->set('Authorization', 'Bearer token');

        $isSupported = $this->authenticator->supports($request);

        $this->assertTrue($isSupported);
    }

    public function testSupportsFail(): void
    {
        $request = new Request();

        $isSupported = $this->authenticator->supports($request);

        $this->assertFalse($isSupported);
    }

    public function testAuthenticateSuccess(): void
    {
        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('app_name'));
        $this->params->get('jwt_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_key'));
        $this->params->get('jwt_algorithm')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_algorithm'));

        $this->userRepository->findOne($this->user->getId())->shouldBeCalledOnce()->willReturn($this->user);

        $payload = [
            'user_id' => $this->user->getId(),
            'user_secret' => $this->user->getSecret(),
            'app_secret' => self::$container->getParameter('app_secret'),
            'iss' => self::$container->getParameter('app_name'),
            'sub' => $this->user->getId(),
            'aud' => self::$container->getParameter('app_name'),
            'exp' => (new DateTime())->modify('+1 month')->getTimestamp(),
            'nbf' => (new DateTime())->getTimestamp(),
            'iat' => (new DateTime())->getTimestamp(),
            'jti' => Uuid::v4()->toRfc4122(),
        ];

        $token = JWT::encode($payload, self::$container->getParameter('jwt_key'), self::$container->getParameter('jwt_algorithm'));

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer '.$token);

        $passport = $this->authenticator->authenticate($request);

        $this->assertEquals($passport->getUser()->getId(), $this->user->getId());
    }

    public function testAuthenticateFailInvalidToken(): void
    {
        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldNotBeCalled();
        $this->params->get('jwt_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_key'));
        $this->params->get('jwt_algorithm')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_algorithm'));

        $this->userRepository->findOne($this->user->getId())->shouldNotBeCalled();

        $payload = [
            'user_id' => $this->user->getId(),
            'user_secret' => $this->user->getSecret(),
            'app_secret' => 'wrong_app_secret',
            'iss' => self::$container->getParameter('app_name'),
            'sub' => $this->user->getId(),
            'aud' => self::$container->getParameter('app_name'),
            'exp' => (new DateTime())->modify('+1 month')->getTimestamp(),
            'nbf' => (new DateTime())->getTimestamp(),
            'iat' => (new DateTime())->getTimestamp(),
            'jti' => Uuid::v4()->toRfc4122(),
        ];

        $token = JWT::encode($payload, self::$container->getParameter('jwt_key'), self::$container->getParameter('jwt_algorithm'));

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer '.$token);

        $this->expectException(AuthenticationException::class);

        $passport = $this->authenticator->authenticate($request);
    }

    public function testAuthenticateFailUsernameNotFound(): void
    {
        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldBeCalledTimes(2)->willReturn(self::$container->getParameter('app_name'));
        $this->params->get('jwt_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_key'));
        $this->params->get('jwt_algorithm')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_algorithm'));

        $this->userRepository->findOne($this->user->getId())->shouldBeCalledOnce()->willReturn($this->user);

        $payload = [
            'user_id' => $this->user->getId(),
            'user_secret' => 'wrong_user_secret',
            'app_secret' => self::$container->getParameter('app_secret'),
            'iss' => self::$container->getParameter('app_name'),
            'sub' => $this->user->getId(),
            'aud' => self::$container->getParameter('app_name'),
            'exp' => (new DateTime())->modify('+1 month')->getTimestamp(),
            'nbf' => (new DateTime())->getTimestamp(),
            'iat' => (new DateTime())->getTimestamp(),
            'jti' => Uuid::v4()->toRfc4122(),
        ];

        $token = JWT::encode($payload, self::$container->getParameter('jwt_key'), self::$container->getParameter('jwt_algorithm'));

        $request = new Request();
        $request->headers->set('Authorization', 'Bearer '.$token);

        $this->expectException(AuthenticationException::class);

        $passport = $this->authenticator->authenticate($request);
    }
}
