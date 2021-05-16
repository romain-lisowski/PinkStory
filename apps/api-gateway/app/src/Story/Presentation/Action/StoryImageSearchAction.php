<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\Story\Query\Query\StoryImageSearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story-image/search", name="story_image_search", methods={"GET"})
 * @ParamConverter("query", converter="request_body", options={"mapping": {"language_id" = "request.current_language.id"}})
 */
final class StoryImageSearchAction
{
    private QueryBusInterface $queryBus;
    private ResponderInterface $responder;

    public function __construct(QueryBusInterface $queryBus, ResponderInterface $responder)
    {
        $this->queryBus = $queryBus;
        $this->responder = $responder;
    }

    public function __invoke(StoryImageSearchQuery $query): Response
    {
        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
