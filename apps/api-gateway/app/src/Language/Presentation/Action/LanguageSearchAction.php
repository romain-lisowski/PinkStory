<?php

declare(strict_types=1);

namespace App\Language\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\Language\Query\Query\LanguageSearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/language/search", name="language_search", methods={"GET"})
 * @ParamConverter("query", converter="request_body")
 */
final class LanguageSearchAction
{
    private QueryBusInterface $queryBus;
    private ResponderInterface $responder;

    public function __construct(QueryBusInterface $queryBus, ResponderInterface $responder)
    {
        $this->queryBus = $queryBus;
        $this->responder = $responder;
    }

    public function __invoke(LanguageSearchQuery $query): Response
    {
        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
