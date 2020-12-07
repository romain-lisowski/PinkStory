<?php

declare(strict_types=1);

namespace App\Language\Action;

use App\Action\AbstractAction;
use App\Language\Query\LanguageSearchQuery;
use App\Language\Query\LanguageSearchQueryHandler;
use App\Responder\ResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/language/search", name="language_search", methods={"GET"})
 */
final class LanguageSearchAction extends AbstractAction
{
    private LanguageSearchQueryHandler $handler;
    private ResponderInterface $responder;

    public function __construct(LanguageSearchQueryHandler $handler, ResponderInterface $responder)
    {
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $query = new LanguageSearchQuery();

        return $this->responder->render([
            'languages' => $this->handler->setQuery($query)->handle(),
        ], ['groups' => 'medium']);
    }
}
