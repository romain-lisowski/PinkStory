<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account", name="account", methods={"GET"})
 */
final class AccountGetAction extends AbstractAction
{
    private ResponderInterface $responder;
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager)
    {
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
    }

    public function run(Request $request): Response
    {
        return $this->responder->render([
            'user' => $this->userSecurityManager->getCurrentUser(),
        ]);
    }
}
