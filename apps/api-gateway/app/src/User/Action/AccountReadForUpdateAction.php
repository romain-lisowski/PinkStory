<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\Security\AuthorizationManagerInterface;
use App\User\Security\UserSecurityManagerInterface;
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
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(AuthorizationManagerInterface $authorizationManager, ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager)
    {
        $this->authorizationManager = $authorizationManager;
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
    }

    public function run(Request $request): Response
    {
        $this->authorizationManager->isGranted(UserableVoter::UPDATE, $this->userSecurityManager->getUser());

        return $this->responder->render([
            'user' => $this->userSecurityManager->getUser(),
        ]);
    }
}
