<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Responder\ResponderInterface;
use App\User\Command\UserUpdateEmailCommand;
use App\User\Command\UserUpdateEmailCommandHandler;
use App\User\Security\UserSecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/update-email", name="user_update_email", methods={"PATCH"})
 */
final class UserUpdateEmailAction
{
    private FormFactoryInterface $formFactory;
    private ResponderInterface $responder;
    private UserSecurityInterface $security;
    private UserUpdateEmailCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, ResponderInterface $responder, UserSecurityInterface $security, UserUpdateEmailCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->security = $security;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserUpdateEmailCommand();
            $command->id = $this->security->getUser()->getId();

            $form = $this->formFactory->create(UserUpdateEmailCommandFormType::class, $command);

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
