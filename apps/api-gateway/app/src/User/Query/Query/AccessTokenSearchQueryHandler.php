<?php

declare(strict_types=1);

namespace App\User\Query\Query;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Security\AuthorizationCheckerInterface;
use App\Common\Domain\Validator\ValidatorInterface;
use App\Common\Query\Query\QueryHandlerInterface;
use App\User\Query\Repository\AccessTokenRepositoryInterface;

final class AccessTokenSearchQueryHandler implements QueryHandlerInterface
{
    private AccessTokenRepositoryInterface $accessTokenRepository;
    private AuthorizationCheckerInterface $authorizationChecker;
    private ValidatorInterface $validator;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository, AuthorizationCheckerInterface $authorizationChecker, ValidatorInterface $validator)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->authorizationChecker = $authorizationChecker;
        $this->validator = $validator;
    }

    public function __invoke(AccessTokenSearchQuery $query): array
    {
        $this->validator->validate($query);

        $accessTokens = $this->accessTokenRepository->findBySearch($query);

        foreach ($accessTokens as $accessToken) {
            $this->authorizationChecker->isGranted(EditableInterface::READ, $accessToken);
        }

        return [
            'access-tokens' => $accessTokens,
        ];
    }
}
