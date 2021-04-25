<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\User\Query\Query\AccessTokenSearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/access-token/search", name="account_access_token_search", methods={"GET"})
 * @ParamConverter("query", converter="request_body", options={"mapping": {"user_id" = "security.user.id"}})
 */
final class AccountAccessTokenSearchAction
{
    private QueryBusInterface $queryBus;
    private ResponderInterface $responder;

    public function __construct(QueryBusInterface $queryBus, ResponderInterface $responder)
    {
        $this->queryBus = $queryBus;
        $this->responder = $responder;
    }

    public function __invoke(AccessTokenSearchQuery $query): Response
    {
        $result = $this->queryBus->dispatch($query);

        return $this->responder->render([
            'access-tokens' => $result,
        ]);
    }
}
