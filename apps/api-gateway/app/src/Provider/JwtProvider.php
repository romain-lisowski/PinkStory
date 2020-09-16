<?php

declare(strict_types=1);

namespace App\Provider;

use App\Exception\InvalidSSLKeyException;
use DateTime;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Uid\Uuid;

final class JwtProvider
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function __invoke()
    {
        $payload = [
            'app_secret' => $this->params->get('app_secret'),
            'iss' => $this->params->get('app_name'),
            'sub' => $this->params->get('app_name'),
            'aud' => $this->params->get('app_name'),
            'exp' => (new DateTime())->modify('+1 day')->getTimestamp(),
            'nbf' => (new DateTime())->getTimestamp(),
            'iat' => (new DateTime())->getTimestamp(),
            'jti' => Uuid::v4()->toRfc4122(),
            'mercure' => [
                'publish' => ['*'],
            ],
        ];

        $privateKey = openssl_pkey_get_private(file_get_contents($this->params->get('jwt_private_key')), $this->params->get('jwt_pass_phrase'));

        if (false === $privateKey) {
            throw new InvalidSSLKeyException();
        }

        return JWT::encode($payload, $privateKey, $this->params->get('jwt_algorithm'));
    }
}
