<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Responder\ResponderInterface;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommand;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommandFormType;
use App\User\Command\UserRegeneratePasswordForgottenSecretCommandHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/account/regenerate-password-forgotten-secret", name="account_regenerate_password_forgotten_secret", methods={"PATCH"})
 */
final class AccountRegeneratePasswordForgottenSecretAction
{
    private FormFactoryInterface $formFactory;
    private ResponderInterface $responder;
    private UserRegeneratePasswordForgottenSecretCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, ResponderInterface $responder, UserRegeneratePasswordForgottenSecretCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserRegeneratePasswordForgottenSecretCommand();

            $form = $this->formFactory->create(UserRegeneratePasswordForgottenSecretCommandFormType::class, $command);

            $form->handleRequest($request);

            if (false === $form->isSubmitted()) {
                throw new NotSubmittedFormException();
            }

            if (false === $form->isValid()) {
                throw new InvalidFormException($form->getErrors(true));
            }

            $this->handler->setCommand($command)->handle();

            return $this->responder->render();
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}