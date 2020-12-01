<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Command\UserRemoveImageCommand;
use App\User\Command\UserRemoveImageCommandHandler;
use App\User\Security\UserSecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/remove-image", name="user_remove_image", methods={"DELETE"})
 */
final class UserRemoveImageAction
{
    private ResponderInterface $responder;
    private UserRemoveImageCommandHandler $handler;
    private UserSecurityInterface $security;

    public function __construct(ResponderInterface $responder, UserRemoveImageCommandHandler $handler, UserSecurityInterface $security)
    {
        $this->security = $security;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserRemoveImageCommand();
            $command->id = $this->security->getUser()->getId();

            $this->handler->setCommand($command)->handle();

            return $this->responder->render();
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
