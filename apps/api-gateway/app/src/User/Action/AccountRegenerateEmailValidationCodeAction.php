<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Command\UserRegenerateEmailValidationCodeCommand;
use App\User\Command\UserRegenerateEmailValidationCodeCommandHandler;
use App\User\Security\UserSecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/regenerate-email-validation-code", name="account_regenerate_email_validation_code", methods={"GET"})
 */
final class AccountRegenerateEmailValidationCodeAction
{
    private ResponderInterface $responder;
    private UserRegenerateEmailValidationCodeCommandHandler $handler;
    private UserSecurityInterface $security;

    public function __construct(ResponderInterface $responder, UserRegenerateEmailValidationCodeCommandHandler $handler, UserSecurityInterface $security)
    {
        $this->responder = $responder;
        $this->handler = $handler;
        $this->security = $security;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserRegenerateEmailValidationCodeCommand();
            $command->id = $this->security->getUser()->getId();

            $this->handler->setCommand($command)->setCurrentUser($this->security->getUser())->handle();

            return $this->responder->render();
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}