<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
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
    private FormManagerInterface $formManager;
    private StoryImageSearchQueryHandler $handler;
    private ResponderInterface $responder;

    public function __construct(FormManagerInterface $formManager, StoryImageSearchQueryHandler $handler, ResponderInterface $responder)
    {
        $this->formManager = $formManager;
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $query = new StoryImageSearchQuery();
        $query->languageId = $request->get('current-language')->getId();

        $this->formManager->initForm($query)->handleRequest($request);

        return $this->responder->render(
            $this->handler->setQuery($query)->handle(),
        );
    }
}
