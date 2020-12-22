<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\Story\Command\StoryUpdateCommand;
use App\Story\Command\StoryUpdateCommandHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/story/update/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_update", methods={"PATCH"})
 */
final class StoryUpdateAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private StoryUpdateCommandHandler $handler;
    private ResponderInterface $responder;

    public function __construct(FormManagerInterface $formManager, StoryUpdateCommandHandler $handler, ResponderInterface $responder)
    {
        $this->formManager = $formManager;
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $command = new StoryUpdateCommand();
        $command->id = $request->attributes->get('id');

        $this->formManager->initForm($command)->handleRequest($request);

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
