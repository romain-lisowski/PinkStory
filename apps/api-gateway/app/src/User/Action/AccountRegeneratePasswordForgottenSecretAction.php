<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommand;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommandHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/regenerate-password-forgotten-secret", name="account_regenerate_password_forgotten_secret", methods={"PATCH"})
 */
final class AccountRegeneratePasswordForgottenSecretAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserRegeneratePasswordForgottenSecretCommandHandler $handler;

    public function __construct(FormManagerInterface $formManager, ResponderInterface $responder, UserRegeneratePasswordForgottenSecretCommandHandler $handler)
    {
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserRegeneratePasswordForgottenSecretCommand();

        $this->formManager->initForm($command)->handleRequest($request);

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
