<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserGenerateAuthTokenCommand;
use App\User\Command\UserGenerateAuthTokenCommandFormType;
use App\User\Command\UserGenerateAuthTokenCommandHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/login", name="acount_login", methods={"POST"})
 */
final class AccountLoginAction extends AbstractAction
{
    private FormFactoryInterface $formFactory;
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserGenerateAuthTokenCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, FormManagerInterface $formManager, ResponderInterface $responder, UserGenerateAuthTokenCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserGenerateAuthTokenCommand();

        $form = $this->formFactory->create(UserGenerateAuthTokenCommandFormType::class, $command);
        $this->formManager->setForm($form)->handleRequest($request);

        $token = $this->handler->setCommand($command)->handle();

        return $this->responder->render([
            'token' => $token,
        ]);
    }
}
