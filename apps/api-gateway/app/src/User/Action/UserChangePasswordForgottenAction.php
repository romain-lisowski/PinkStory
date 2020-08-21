<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Responder\ResponderInterface;
use App\User\Command\UserChangePasswordForgottenCommand;
use App\User\Command\UserChangePasswordForgottenCommandFormType;
use App\User\Command\UserChangePasswordForgottenCommandHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/users/change-password-forgotten/{secret<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="user_change_password_forgotten", methods={"POST"})
 */
final class UserChangePasswordForgottenAction
{
    private FormFactoryInterface $formFactory;
    private ResponderInterface $responder;
    private UserChangePasswordForgottenCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, ResponderInterface $responder, UserChangePasswordForgottenCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, string $secret): Response
    {
        try {
            $command = new UserChangePasswordForgottenCommand();
            $command->secret = $secret;

            $form = $this->formFactory->create(UserChangePasswordForgottenCommandFormType::class, $command);

            $form->handleRequest($request);

            if (false === $form->isSubmitted()) {
                throw new NotSubmittedFormException();
            }

            if (false === $form->isValid()) {
                throw new InvalidFormException($form->getErrors(true));
            }

            $this->handler->handle($command);

            return $this->responder->render();
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
