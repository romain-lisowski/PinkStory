<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\Security\AuthorizationManagerInterface;
use App\User\Security\UserSecurityInterface;
use App\User\Voter\UserableVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/update", name="account_update", methods={"GET"})
 */
final class AccountReadForUpdateAction extends AbstractAction
{
    private AuthorizationManagerInterface $authorizationManager;
    private ResponderInterface $responder;
    private UserSecurityInterface $security;

    public function __construct(AuthorizationManagerInterface $authorizationManager, ResponderInterface $responder, UserSecurityInterface $security)
    {
        $this->authorizationManager = $authorizationManager;
        $this->responder = $responder;
        $this->security = $security;
    }

    public function run(Request $request): Response
    {
        $this->authorizationManager->isGranted(UserableVoter::UPDATE, $this->security->getUser());

        return $this->responder->render([
            'user' => $this->security->getUser(),
        ], ['groups' => 'full']);
    }
}
