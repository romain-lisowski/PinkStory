<?php

declare(strict_types=1);

namespace App\User\Security;

use App\User\Exception\InvalidTokenException;
use App\User\Exception\NoTokenProvidedException;
use App\User\Repository\Dto\UserRepositoryInterface;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class UserAuthenticator extends AbstractAuthenticator
{
    private ParameterBagInterface $params;
    private UserRepositoryInterface $userRepository;

    public function __construct(ParameterBagInterface $params, UserRepositoryInterface $userRepository)
    {
        $this->params = $params;
        $this->userRepository = $userRepository;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        // look for header "Authorization: Bearer <token>"
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function authenticate(Request $request): PassportInterface
    {
        try {
            $header = $request->headers->get('Authorization');
            $token = substr($header, 7);

            if (false === $token) {
                throw new NoTokenProvidedException();
            }

            $payload = JWT::decode($token, file_get_contents($this->params->get('jwt_public_key')), [$this->params->get('jwt_algorithm')]);

            if ($payload->app_secret !== $this->params->get('app_secret')
                || $payload->iss !== $this->params->get('project_name')
                || $payload->aud !== $this->params->get('project_name')) {
                throw new InvalidTokenException();
            }

            $user = $this->userRepository->findCurrent($payload->user_id);

            if (null === $user
                || $user->getId() !== $payload->sub
                || $user->getSecret() !== $payload->user_secret) {
                throw new UsernameNotFoundException();
            }

            return new SelfValidatingPassport($user);
        } catch (Exception $e) {
            throw new AuthenticationException('', 0, new InvalidTokenException());
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $e): ?Response
    {
        throw new UnauthorizedHttpException('Bearer', null, $e);
    }
}
