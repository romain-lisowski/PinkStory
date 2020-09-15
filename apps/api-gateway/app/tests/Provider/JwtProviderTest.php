<?php

declare(strict_types=1);

namespace App\Test\Provider;

use App\Provider\JwtProvider;
use Firebase\JWT\JWT;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @internal
 * @coversNothing
 */
final class JwtProviderTest extends KernelTestCase
{
    private Prophet $prophet;
    private JwtProvider $provider;
    private $params;

    public function setUp(): void
    {
        self::bootKernel();

        $this->prophet = new Prophet();

        $this->params = $this->prophet->prophesize(ParameterBagInterface::class);

        $this->provider = new JwtProvider($this->params->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testInvokeSucess(): void
    {
        $this->params->get('app_secret')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('app_secret'));
        $this->params->get('app_name')->shouldBeCalledTimes(3)->willReturn(self::$container->getParameter('app_name'));
        $this->params->get('jwt_key')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_key'));
        $this->params->get('jwt_algorithm')->shouldBeCalledOnce()->willReturn(self::$container->getParameter('jwt_algorithm'));

        $token = $this->provider->__invoke();

        $payload = JWT::decode($token, self::$container->getParameter('jwt_key'), [self::$container->getParameter('jwt_algorithm')]);

        $this->assertEquals($payload->app_secret, self::$container->getParameter('app_secret'));
        $this->assertEquals($payload->iss, self::$container->getParameter('app_name'));
        $this->assertEquals($payload->sub, self::$container->getParameter('app_name'));
        $this->assertEquals($payload->aud, self::$container->getParameter('app_name'));
        $this->assertEquals($payload->mercure->publish[0], '*');
    }
}
