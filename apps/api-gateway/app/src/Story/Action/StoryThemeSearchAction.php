<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\Story\Query\StoryThemeSearchQuery;
use App\Story\Query\StoryThemeSearchQueryHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story-theme/search", name="story_theme_search", methods={"GET"})
 */
final class StoryThemeSearchAction extends AbstractAction
{
    private StoryThemeSearchQueryHandler $handler;
    private ResponderInterface $responder;

    public function __construct(StoryThemeSearchQueryHandler $handler, ResponderInterface $responder)
    {
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $query = new StoryThemeSearchQuery();
        $query->languageId = $request->get('current-language')->getId();

        return $this->responder->render([
            'story_themes' => $this->handler->setQuery($query)->handle(),
        ]);
    }
}
