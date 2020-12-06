<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Command\UserRemoveImageCommand;
use App\User\Command\UserRemoveImageCommandHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/remove-image", name="account_remove_image", methods={"DELETE"})
 */
final class AccountRemoveImageAction extends AbstractAction
{
    private ResponderInterface $responder;
    private UserRemoveImageCommandHandler $handler;
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(ResponderInterface $responder, UserRemoveImageCommandHandler $handler, UserSecurityManagerInterface $userSecurityManager)
    {
        $this->responder = $responder;
        $this->handler = $handler;
        $this->userSecurityManager = $userSecurityManager;
    }

    public function run(Request $request): Response
    {
        $command = new UserRemoveImageCommand();
        $command->id = $this->userSecurityManager->getUser()->getId();

        $this->handler->setCommand($command)->setCurrentUser($this->userSecurityManager->getUser())->handle();

        return $this->responder->render();
    }
}
