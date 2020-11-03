<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Responder\ResponderInterface;
use App\User\Command\UserUpdateInformationCommand;
use App\User\Command\UserUpdateInformationCommandHandler;
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
 * @Route("/users/update-information", name="user_update_information", methods={"PATCH"})
 */
final class UserUpdateInformationAction
{
    private FormFactoryInterface $formFactory;
    private Security $security;
    private ResponderInterface $responder;
    private UserUpdateInformationCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, Security $security, ResponderInterface $responder, UserUpdateInformationCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserUpdateInformationCommand();
            $command->id = $this->security->getUser()->getId();

            $form = $this->formFactory->create(UserUpdateInformationCommandFormType::class, $command);

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
            dump($e);
            exit;

            throw new BadRequestHttpException(null, $e);
        }
    }
}
