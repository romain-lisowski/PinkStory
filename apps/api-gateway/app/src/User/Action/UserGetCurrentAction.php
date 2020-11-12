<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Responder\ResponderInterface;
use App\User\Security\UserSecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/users/current", name="user_current", methods={"GET"})
 */
final class UserGetCurrentAction
{
    private ResponderInterface $responder;
    private UserSecurityInterface $security;

    public function __construct(ResponderInterface $responder, UserSecurityInterface $security)
    {
        $this->responder = $responder;
        $this->security = $security;
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
