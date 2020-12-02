<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Security\UserSecurityInterface;
use App\User\Voter\UserableVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/current/update", name="user_current_update", methods={"GET"})
 */
final class UserGetCurrentAction
{
    private AuthorizationCheckerInterface $authorizationChecker;
    private ResponderInterface $responder;
    private UserSecurityInterface $security;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, ResponderInterface $responder, UserSecurityInterface $security)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->responder = $responder;
        $this->security = $security;
    }

    public function __invoke(Request $request): Response
    {
        try {
            if (false === $this->authorizationChecker->isGranted(UserableVoter::UPDATE, $this->security->getUser())) {
                throw new AccessDeniedException();
            }

            return $this->responder->render([
                'user' => $this->security->getUser(),
            ], ['groups' => 'full']);
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
