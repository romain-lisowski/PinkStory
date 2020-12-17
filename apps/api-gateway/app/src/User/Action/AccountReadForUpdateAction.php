<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Query\UserReadForUpdateQuery;
use App\User\Query\UserReadForUpdateQueryHandler;
use App\User\Security\UserSecurityManagerInterface;
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
    private ResponderInterface $responder;
    private UserReadForUpdateQueryHandler $handler;
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(ResponderInterface $responder, UserReadForUpdateQueryHandler $handler, UserSecurityManagerInterface $userSecurityManager)
    {
        $this->responder = $responder;
        $this->handler = $handler;
        $this->userSecurityManager = $userSecurityManager;
    }

    public function run(Request $request): Response
    {
        $query = new UserReadForUpdateQuery();
        $query->id = $this->userSecurityManager->getCurrentUser()->getId();

        return $this->responder->render([
            'user' => $this->handler->setQuery($query)->handle(),
        ]);
    }
}
