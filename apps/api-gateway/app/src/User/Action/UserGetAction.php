<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Query\UserGetQuery;
use App\User\Query\UserGetQueryHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="user_get", methods={"GET"})
 */
final class UserGetAction extends AbstractAction
{
    private ResponderInterface $responder;
    private UserGetQueryHandler $handler;

    public function __construct(ResponderInterface $responder, UserGetQueryHandler $handler)
    {
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $query = new UserGetQuery();
        $query->id = (string) $request->attributes->get('id');

        return $this->responder->render([
            'user' => $this->handler->setQuery($query)->handle(),
        ]);
    }
}
