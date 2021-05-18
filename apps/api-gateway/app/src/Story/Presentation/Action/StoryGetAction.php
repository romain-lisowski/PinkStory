<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Presentation\Response\ResponderInterface;
use App\Common\Query\Query\QueryBusInterface;
use App\Story\Domain\Model\Story;
use App\Story\Query\Query\StoryGetQuery;
use App\User\Domain\Security\SecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_get", methods={"GET"})
 * @ParamConverter("query", converter="request_body")
 * @ParamConverter("story", converter="entity", options={"expr": "repository.findOne(id)"})
 */
final class StoryGetAction
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

    public function __invoke(Request $request, StoryGetQuery $query, Story $story): Response
    {
        $query
            ->setId($story->getId())
            ->setLanguageId($request->get('current-language')->getId())
            ->setUserId(null !== $this->security->getUser() ? $this->security->getUser()->getId() : null)
        ;

        $result = $this->queryBus->dispatch($query);

        return $this->responder->render($result);
    }
}
