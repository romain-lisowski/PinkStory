<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Language\Model\Dto\Language;
use App\Responder\ResponderInterface;
use App\Story\Query\StorySearchQuery;
use App\Story\Query\StorySearchQueryHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story/search", name="story_search", methods={"GET"})
 */
final class StorySearchAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private StorySearchQueryHandler $handler;
    private ResponderInterface $responder;

    public function __construct(FormManagerInterface $formManager, StorySearchQueryHandler $handler, ResponderInterface $responder)
    {
        $this->formManager = $formManager;
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $query = new StorySearchQuery();
        $query->languageId = $request->get('current-language')->getId();
        $query->readingLanguageIds = Language::extractIds($request->get('current-reading-languages'));

        $this->formManager->initForm($query)->handleRequest($request);

        return $this->responder->render(
            $this->handler->setQuery($query)->handle(),
        );
    }
}
