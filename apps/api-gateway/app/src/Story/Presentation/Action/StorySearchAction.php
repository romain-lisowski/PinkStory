<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\Story\Query\Query\StorySearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story/search", name="story_search", methods={"GET"})
 * @ParamConverter("query", converter="request_body", options={"mapping": {"language_id" = "request.current_language.id", "reading_language_ids" = "request.current_reading_languages.ids"}})
 */
final class StorySearchAction
{
    private QueryBusInterface $queryBus;
    private ResponderInterface $responder;

    public function __construct(QueryBusInterface $queryBus, ResponderInterface $responder)
    {
        $this->queryBus = $queryBus;
        $this->responder = $responder;
    }

    public function __invoke(StorySearchQuery $query): Response
    {
        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
