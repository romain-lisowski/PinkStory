<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserValidateEmailCommand;
use App\User\Command\UserValidateEmailCommandHandler;
use App\User\Security\UserSecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/validate-email", name="account_validate_email", methods={"PATCH"})
 */
final class AccountValidateEmailAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserSecurityInterface $security;
    private UserValidateEmailCommandHandler $handler;

    public function __construct(FormManagerInterface $formManager, ResponderInterface $responder, UserSecurityInterface $security, UserValidateEmailCommandHandler $handler)
    {
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->security = $security;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserValidateEmailCommand();
        $command->id = $this->security->getUser()->getId();

        $this->formManager->initForm($command)->handleRequest($request);

        $this->handler->setCommand($command)->setCurrentUser($this->security->getUser())->handle();

        return $this->responder->render();
    }
}
