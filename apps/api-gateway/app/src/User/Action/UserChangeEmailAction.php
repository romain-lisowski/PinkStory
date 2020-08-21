<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Responder\ResponderInterface;
use App\User\Command\UserChangeEmailCommand;
use App\User\Command\UserChangeEmailCommandFormType;
use App\User\Command\UserChangeEmailCommandHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/change-email", name="user_change_email", methods={"POST"})
 */
final class UserChangeEmailAction
{
    private FormFactoryInterface $formFactory;
    private Security $security;
    private ResponderInterface $responder;
    private UserChangeEmailCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, Security $security, ResponderInterface $responder, UserChangeEmailCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserChangeEmailCommand();
            $command->id = $this->security->getUser()->getId();

            $form = $this->formFactory->create(UserChangeEmailCommandFormType::class, $command);

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
