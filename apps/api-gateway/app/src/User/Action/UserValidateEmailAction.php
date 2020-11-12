<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Command\UserValidateEmailCommand;
use App\User\Command\UserValidateEmailCommandHandler;
use App\User\Security\UserSecurity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/validate-email/{secret<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="user_validate_email", methods={"GET"})
 */
final class UserValidateEmailAction
{
    private ResponderInterface $responder;
    private UserSecurity $security;
    private UserValidateEmailCommandHandler $handler;

    public function __construct(ResponderInterface $responder, UserSecurity $security, UserValidateEmailCommandHandler $handler)
    {
        $this->responder = $responder;
        $this->security = $security;
        $this->handler = $handler;
    }

    public function __invoke(Request $request, string $secret): Response
    {
        try {
            $command = new UserValidateEmailCommand();
            $command->id = $this->security->getUser()->getId();
            $command->secret = $secret;

            $this->handler->handle($command);

            return $this->responder->render();
        } catch (AccessDeniedException $e) {
            throw new AccessDeniedHttpException(null, $e);
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
