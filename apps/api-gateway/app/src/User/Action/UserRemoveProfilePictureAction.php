<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Command\UserRemoveProfilePictureCommand;
use App\User\Command\UserRemoveProfilePictureCommandHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/remove-profile-picture", name="user_remove_profile_picture", methods={"DELETE"})
 */
final class UserRemoveProfilePictureAction
{
    private Security $security;
    private ResponderInterface $responder;
    private UserRemoveProfilePictureCommandHandler $handler;

    public function __construct(Security $security, ResponderInterface $responder, UserRemoveProfilePictureCommandHandler $handler)
    {
        $this->security = $security;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new UserRemoveProfilePictureCommand();
            $command->id = $this->security->getUser()->getId();

            $this->handler->handle($command);

            return $this->responder->render();
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
