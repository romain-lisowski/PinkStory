<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserUpdateImageCommand;
use App\User\Command\UserUpdateImageCommandHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/update-image", name="account_update_image", methods={"PATCH"})
 */
final class AccountUpdateImageAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserSecurityManagerInterface $userSecurityManager;
    private UserUpdateImageCommandHandler $handler;

    public function __construct(FormManagerInterface $formManager, ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager, UserUpdateImageCommandHandler $handler)
    {
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserUpdateImageCommand();
        $command->id = $this->userSecurityManager->getUser()->getId();

        $this->formManager->initForm($command)->handleRequest($request);

        $this->handler->setCommand($command)->setCurrentUser($this->userSecurityManager->getUser())->handle();

        return $this->responder->render();
    }
}
