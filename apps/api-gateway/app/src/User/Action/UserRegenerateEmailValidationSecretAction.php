<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Command\UserRegenerateEmailValidationSecretCommand;
use App\User\Command\UserRegenerateEmailValidationSecretCommandHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/regenerate-email-validation-secret", name="user_regenerate_email_validation_secret", methods={"GET"})
 */
final class UserRegenerateEmailValidationSecretAction
{
    private Security $security;
    private ResponderInterface $responder;
    private UserRegenerateEmailValidationSecretCommandHandler $handler;

    public function __construct(Security $security, ResponderInterface $responder, UserRegenerateEmailValidationSecretCommandHandler $handler)
    {
        $this->security = $security;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserRegenerateEmailValidationSecretCommand();
            $command->id = $this->security->getUser()->getId();

            $this->handler->handle($command);

            return $this->responder->render();
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
