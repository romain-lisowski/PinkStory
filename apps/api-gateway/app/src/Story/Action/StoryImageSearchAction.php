<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\Story\Query\StoryImageSearchQuery;
use App\Story\Query\StoryImageSearchQueryHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story-image/search", name="story_image_search", methods={"GET"})
 */
final class StoryImageSearchAction extends AbstractAction
{
    private StoryImageSearchQueryHandler $handler;
    private ResponderInterface $responder;

    public function __construct(StoryImageSearchQueryHandler $handler, ResponderInterface $responder)
    {
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $query = new StoryImageSearchQuery();

        return $this->responder->render([
            'story-images' => $this->handler->setQuery($query)->handle(),
        ]);
    }
}
