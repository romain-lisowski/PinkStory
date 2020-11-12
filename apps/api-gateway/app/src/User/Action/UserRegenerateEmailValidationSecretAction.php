<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Command\UserRegenerateEmailValidationSecretCommand;
use App\User\Command\UserRegenerateEmailValidationSecretCommandHandler;
use App\User\Security\UserSecurity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/regenerate-email-validation-secret", name="user_regenerate_email_validation_secret", methods={"GET"})
 */
final class UserRegenerateEmailValidationSecretAction
{
    private ResponderInterface $responder;
    private UserRegenerateEmailValidationSecretCommandHandler $handler;
    private UserSecurity $security;

    public function __construct(ResponderInterface $responder, UserRegenerateEmailValidationSecretCommandHandler $handler, UserSecurity $security)
    {
        $this->responder = $responder;
        $this->handler = $handler;
        $this->security = $security;
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
