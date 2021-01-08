<?php

declare(strict_types=1);

namespace App\User\Security;

use App\User\Exception\InvalidTokenException;
use App\User\Exception\NoTokenProvidedException;
use App\User\Model\Dto\CurrentUser;
use Exception;
use Firebase\JWT\JWT;
use LogicException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

final class UserAuthenticator extends AbstractAuthenticator
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
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

            $passport = new SelfValidatingPassport(new UserBadge($payload->user_id));
            $passport->setAttribute('payload_app_secret', $payload->app_secret);
            $passport->setAttribute('payload_iss', $payload->iss);
            $passport->setAttribute('payload_aud', $payload->aud);
            $passport->setAttribute('payload_sub', $payload->sub);
            $passport->setAttribute('payload_user_secret', $payload->user_secret);

            return $passport;
        } catch (Exception $e) {
            throw new AuthenticationException('', 0, new InvalidTokenException());
        }
    }

    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
    {
        if (!$passport instanceof UserPassportInterface) {
            throw new LogicException(sprintf('Passport does not contain a user, overwrite "createAuthenticatedToken()" in "%s" to create a custom authenticated token.', \get_class($this)));
        }

        if (!$passport instanceof Passport) {
            throw new LogicException('Passport is not valid.');
        }

        $user = $passport->getUser();

        if (!$user instanceof CurrentUser
            || $passport->getAttribute('payload_app_secret') !== $this->params->get('app_secret')
            || $passport->getAttribute('payload_iss') !== $this->params->get('project_name')
            || $passport->getAttribute('payload_aud') !== $this->params->get('project_name')
            || $passport->getAttribute('payload_sub') !== $user->getId()
            || $passport->getAttribute('payload_user_secret') !== $user->getSecret()) {
            throw new AuthenticationException('', 0, new InvalidTokenException());
        }

        return parent::createAuthenticatedToken($passport, $firewallName);
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
