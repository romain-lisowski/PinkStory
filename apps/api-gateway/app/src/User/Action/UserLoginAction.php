<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Responder\ResponderInterface;
use App\User\Command\UserLoginCommand;
use App\User\Command\UserLoginCommandHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/users/login", name="user_login", methods={"POST"})
 */
final class UserLoginAction
{
    private FormFactoryInterface $formFactory;
    private ResponderInterface $responder;
    private UserLoginCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, ResponderInterface $responder, UserLoginCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserLoginCommand();

            $form = $this->formFactory->create(UserLoginCommandFormType::class, $command);

            $form->handleRequest($request);

            if (false === $form->isSubmitted()) {
                throw new NotSubmittedFormException();
            }

            if (false === $form->isValid()) {
                throw new InvalidFormException($form->getErrors(true));
            }

            $token = $this->handler->handle($command);

            return $this->responder->render([
                'token' => $token,
            ]);
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
