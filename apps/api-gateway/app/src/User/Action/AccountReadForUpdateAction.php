<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Security\UserSecurityInterface;
use App\User\Voter\UserableVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/update", name="account_update", methods={"GET"})
 */
final class AccountReadForUpdateAction extends AbstractAction
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

    public function run(Request $request): Response
    {
        if (false === $this->authorizationChecker->isGranted(UserableVoter::UPDATE, $this->security->getUser())) {
            throw new AccessDeniedException();
        }

        return $this->responder->render([
            'user' => $this->security->getUser(),
        ], ['groups' => 'full']);
    }
}
