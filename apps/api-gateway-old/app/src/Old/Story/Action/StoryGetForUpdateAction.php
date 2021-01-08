<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\Story\Query\StoryGetForUpdateQuery;
use App\Story\Query\StoryGetForUpdateQueryHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story/update/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_update_get", methods={"GET"})
 */
final class StoryGetForUpdateAction extends AbstractAction
{
    private StoryGetForUpdateQueryHandler $handler;
    private ResponderInterface $responder;

    public function __construct(StoryGetForUpdateQueryHandler $handler, ResponderInterface $responder)
    {
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $query = new StoryGetForUpdateQuery();
        $query->id = (string) $request->attributes->get('id');
        $query->languageId = $request->get('current-language')->getId();

        return $this->responder->render([
            'story' => $this->handler->setQuery($query)->handle(),
        ]);
    }
}
