<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Query\UserGetForUpdateQuery;
use App\User\Query\UserGetForUpdateQueryHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/update", name="account_update_get", methods={"GET"})
 */
final class AccountGetForUpdateAction extends AbstractAction
{
    private ResponderInterface $responder;
    private UserGetForUpdateQueryHandler $handler;
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(ResponderInterface $responder, UserGetForUpdateQueryHandler $handler, UserSecurityManagerInterface $userSecurityManager)
    {
        $this->responder = $responder;
        $this->handler = $handler;
        $this->userSecurityManager = $userSecurityManager;
    }

    public function run(Request $request): Response
    {
        $query = new UserGetForUpdateQuery();
        $query->id = $this->userSecurityManager->getCurrentUser()->getId();

        return $this->responder->render([
            'user' => $this->handler->setQuery($query)->handle(),
        ]);
    }
}
