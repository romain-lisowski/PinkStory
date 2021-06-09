<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\Story\Query\Query\StoryImageSearchQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story-image/search", name="story_image_search", methods={"GET"})
 * @ParamConverter("query", converter="request_data")
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

    public function __invoke(Request $request, StoryImageSearchQuery $query): Response
    {
        $query->setLanguageId($request->get('current-language')->getId());

        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
