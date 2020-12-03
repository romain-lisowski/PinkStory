<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Responder\ResponderInterface;
use App\User\Command\UserGenerateAuthTokenCommand;
use App\User\Command\UserGenerateAuthTokenCommandFormType;
use App\User\Command\UserGenerateAuthTokenCommandHandler;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/account/login", name="acount_login", methods={"POST"})
 */
final class AccountLoginAction
{
    private FormFactoryInterface $formFactory;
    private ResponderInterface $responder;
    private UserGenerateAuthTokenCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, ResponderInterface $responder, UserGenerateAuthTokenCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserGenerateAuthTokenCommand();

            $form = $this->formFactory->create(UserGenerateAuthTokenCommandFormType::class, $command);

            $form->handleRequest($request);

            if (false === $form->isSubmitted()) {
                throw new NotSubmittedFormException();
            }

            if (false === $form->isValid()) {
                throw new InvalidFormException($form->getErrors(true));
            }

            $token = $this->handler->setCommand($command)->handle();

            return $this->responder->render([
                'token' => $token,
            ]);
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
