<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\User\Domain\Security\SecurityInterface;
use App\User\Query\Query\AccessTokenSearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/access-token/search", name="account_access_token_search", methods={"GET"})
 * @ParamConverter("query", converter="request_data")
 * @IsGranted("ROLE_USER")
 */
final class AccountAccessTokenSearchAction
{
    private QueryBusInterface $queryBus;
    private ResponderInterface $responder;
    private SecurityInterface $security;

    public function __construct(QueryBusInterface $queryBus, ResponderInterface $responder, SecurityInterface $security)
    {
        $this->queryBus = $queryBus;
        $this->responder = $responder;
        $this->security = $security;
    }

    public function __invoke(AccessTokenSearchQuery $query): Response
    {
        $query->setUserId($this->security->getUser()->getId());

        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
