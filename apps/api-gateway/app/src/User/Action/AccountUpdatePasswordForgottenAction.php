<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserUpdatePasswordForgottenCommand;
use App\User\Command\UserUpdatePasswordForgottenCommandFormType;
use App\User\Command\UserUpdatePasswordForgottenCommandHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/update-password-forgotten/{secret<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="account_update_password_forgotten", methods={"PATCH"})
 */
final class AccountUpdatePasswordForgottenAction extends AbstractAction
{
    private FormFactoryInterface $formFactory;
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserUpdatePasswordForgottenCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, FormManagerInterface $formManager, ResponderInterface $responder, UserUpdatePasswordForgottenCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserUpdatePasswordForgottenCommand();
        $command->secret = (string) $request->attributes->get('secret');

        $form = $this->formFactory->create(UserUpdatePasswordForgottenCommandFormType::class, $command);
        $this->formManager->setForm($form)->handleRequest($request);

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
