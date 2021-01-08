<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserUpdatePasswordCommand;
use App\User\Command\UserUpdatePasswordCommandHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/update-password", name="account_update_password", methods={"PATCH"})
 */
final class AccountUpdatePasswordAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserSecurityManagerInterface $userSecurityManager;
    private UserUpdatePasswordCommandHandler $handler;

    public function __construct(FormManagerInterface $formManager, ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager, UserUpdatePasswordCommandHandler $handler)
    {
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserUpdatePasswordCommand();
        $command->id = $this->userSecurityManager->getCurrentUser()->getId();

        $this->formManager->initForm($command)->handleRequest($request);

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
