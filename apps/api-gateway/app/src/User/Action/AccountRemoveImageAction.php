<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Command\UserRemoveImageCommand;
use App\User\Command\UserRemoveImageCommandHandler;
use App\User\Security\UserSecurityInterface;
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
    private UserSecurityInterface $security;

    public function __construct(ResponderInterface $responder, UserRemoveImageCommandHandler $handler, UserSecurityInterface $security)
    {
        $this->security = $security;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserRemoveImageCommand();
        $command->id = $this->security->getUser()->getId();

        $this->handler->setCommand($command)->setCurrentUser($this->security->getUser())->handle();

        return $this->responder->render();
    }
}
