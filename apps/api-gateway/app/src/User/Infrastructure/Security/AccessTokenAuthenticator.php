<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Security;

use App\User\Domain\Repository\AccessTokenRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class AccessTokenAuthenticator extends AbstractAuthenticator
{
    private AccessTokenRepositoryInterface $accessTokenRepository;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
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
            && 1 === preg_match('/Bearer\s(.+)/', $request->headers->get('Authorization'));
    }

    public function authenticate(Request $request): PassportInterface
    {
        try {
            $accessTokenId = substr($request->headers->get('Authorization'), 7);

            $accessToken = $this->accessTokenRepository->findOne($accessTokenId);
            $accessToken->updateLastUpdatedAt();
            $this->accessTokenRepository->flush();

            return new SelfValidatingPassport(new UserBadge($accessToken->getUser()->getId()));
        } catch (\Throwable $e) {
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
        throw new UnauthorizedHttpException('Authorization Bearer', null, $e);
    }
}
