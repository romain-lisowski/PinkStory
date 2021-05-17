<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\Story\Domain\Model\Story;
use App\Story\Query\Query\StoryGetForUpdateQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story/update/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_update_get", methods={"GET"})
 * @ParamConverter("query", converter="request_body")
 * @ParamConverter("story", converter="entity", options={"expr": "repository.findOne(id)"})
 * @IsGranted("ROLE_USER")
 */
final class StoryGetForUpdateAction
{
    private QueryBusInterface $queryBus;
    private ResponderInterface $responder;

    public function __construct(QueryBusInterface $queryBus, ResponderInterface $responder)
    {
        $this->queryBus = $queryBus;
        $this->responder = $responder;
    }

    public function __invoke(StoryGetForUpdateQuery $query, Story $story): Response
    {
        $query->setId($story->getId());

        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
