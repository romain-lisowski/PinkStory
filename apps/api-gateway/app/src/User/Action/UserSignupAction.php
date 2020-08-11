<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\User\Command\UserSignupCommand;
use App\User\Command\UserSignupCommandFormType;
use App\User\Command\UserSignupCommandHandler;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users/signup", name="user_signup", methods={"POST"})
 */
final class UserSignupAction
{
    private FormFactoryInterface $formFactory;
    private UserSignupCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, UserSignupCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserSignupCommand();
            $form = $this->formFactory->create(UserSignupCommandFormType::class, $command);

            $form->handleRequest($request);

            if (false === $form->isSubmitted()) {
                throw new NotSubmittedFormException();
            }

            if (false === $form->isValid()) {
                throw new InvalidFormException($form->getErrors(true));
            }

            $this->handler->handle($command);

            return new JsonResponse($command);
        } catch (Exception $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
