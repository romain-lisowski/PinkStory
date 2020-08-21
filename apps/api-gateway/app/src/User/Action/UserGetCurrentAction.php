<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/current", name="user_current", methods={"GET"})
 */
final class UserGetCurrentAction
{
    private Security $security;
    private ResponderInterface $responder;

    public function __construct(Security $security, ResponderInterface $responder)
    {
        $this->security = $security;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
        try {
            return $this->responder->render([
                'user' => $this->security->getUser(),
            ], ['groups' => 'detail']);
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
