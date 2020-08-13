<?php

declare(strict_types=1);

namespace App\User\Command;

use App\User\Repository\UserRepositoryInterface;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Uid\Uuid;

final class UserLoginCommandHandler
{
    private ParameterBagInterface $params;
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepositoryInterface $userRepository;

    public function __construct(ParameterBagInterface $params, UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $userRepository)
    {
        $this->params = $params;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function handle(UserLoginCommand $command): string
    {
        try {
            $user = $this->userRepository->findOneByEmail($command->email);

            if (false === $this->passwordEncoder->isPasswordValid($user, $command->password)) {
                throw new BadCredentialsException();
            }

            $payload = [
                'user_id' => $user->getId(),
                'user_secret' => $user->getSecret(),
                'app_secret' => $this->params->get('app_secret'),
                'iss' => $this->params->get('app_name'),
                'sub' => $user->getEmail(),
                'aud' => $this->params->get('app_name'),
                'exp' => (new DateTime())->modify('+1 month')->getTimestamp(),
                'nbf' => (new DateTime())->getTimestamp(),
                'iat' => (new DateTime())->getTimestamp(),
                'jti' => Uuid::v4()->toRfc4122(),
            ];

            return JWT::encode($payload, openssl_pkey_get_private(file_get_contents($this->params->get('jwt_private_key')), $this->params->get('jwt_pass_phrase')), 'RS256');
        } catch (NonUniqueResultException | NoResultException $e) {
            throw new BadCredentialsException();
        }
    }
}
