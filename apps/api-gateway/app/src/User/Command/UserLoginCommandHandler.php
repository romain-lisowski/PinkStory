<?php

declare(strict_types=1);

namespace App\User\Command;

use App\Exception\InvalidSSLKeyException;
use App\Exception\ValidatorException;
use App\User\Repository\UserRepositoryInterface;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserLoginCommandHandler
{
    private ParameterBagInterface $params;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ValidatorInterface $validator;
    private UserRepositoryInterface $userRepository;

    public function __construct(ParameterBagInterface $params, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator, UserRepositoryInterface $userRepository)
    {
        $this->params = $params;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(UserLoginCommand $command): string
    {
        try {
            $errors = $this->validator->validate($command);

            if (count($errors) > 0) {
                throw new ValidatorException($errors);
            }

            $user = $this->userRepository->findOneByEmail($command->email);

            if (false === $this->passwordEncoder->isPasswordValid($user, $command->password)) {
                throw new BadCredentialsException();
            }

            $payload = [
                'user_id' => $user->getId(),
                'user_secret' => $user->getSecret(),
                'app_secret' => $this->params->get('app_secret'),
                'iss' => $this->params->get('app_name'),
                'sub' => $user->getId(),
                'aud' => $this->params->get('app_name'),
                'exp' => (new DateTime())->modify('+1 month')->getTimestamp(),
                'nbf' => (new DateTime())->getTimestamp(),
                'iat' => (new DateTime())->getTimestamp(),
                'jti' => Uuid::v4()->toRfc4122(),
            ];

            $privateKey = openssl_pkey_get_private(file_get_contents($this->params->get('jwt_private_key')), $this->params->get('jwt_pass_phrase'));

            if (false === $privateKey) {
                throw new InvalidSSLKeyException();
            }

            return JWT::encode($payload, $privateKey, 'RS256');
        } catch (NonUniqueResultException | NoResultException $e) {
            throw new BadCredentialsException();
        }
    }
}
