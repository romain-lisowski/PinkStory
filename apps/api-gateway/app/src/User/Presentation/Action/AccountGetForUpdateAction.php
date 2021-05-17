<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\User\Domain\Security\SecurityInterface;
use App\User\Query\Query\UserGetForUpdateQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/update", name="account_update_get", methods={"GET"})
 * @ParamConverter("query", converter="request_body")
 * @IsGranted("ROLE_USER")
 */
final class AccountGetForUpdateAction
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

    public function __invoke(UserGetForUpdateQuery $query): Response
    {
        $query->setId($this->security->getUser()->getId());

        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
