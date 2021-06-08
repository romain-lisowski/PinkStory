<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\User\Domain\Model\User;
use App\User\Query\Query\UserGetQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="user_get", methods={"GET"})
 * @ParamConverter("query", converter="request_data")
 * @ParamConverter("user", converter="entity", options={"expr": "repository.findOne(id)"})
 */
final class UserGetAction
{
    private QueryBusInterface $queryBus;
    private ResponderInterface $responder;

    public function __construct(QueryBusInterface $queryBus, ResponderInterface $responder)
    {
        $this->queryBus = $queryBus;
        $this->responder = $responder;
    }

    public function __invoke(UserGetQuery $query, User $user): Response
    {
        $query->setId($user->getId());

        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
