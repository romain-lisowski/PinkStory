<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\Story\Command\StoryUpdateChildrenPositionCommand;
use App\Story\Command\StoryUpdateChildrenPositionCommandHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/story/update-children-position/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_update_children_position", methods={"PATCH"})
 */
final class StoryUpdateChildrenPositionAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private StoryUpdateChildrenPositionCommandHandler $handler;
    private ResponderInterface $responder;

    public function __construct(FormManagerInterface $formManager, StoryUpdateChildrenPositionCommandHandler $handler, ResponderInterface $responder)
    {
        $this->formManager = $formManager;
        $this->handler = $handler;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        $command = new StoryUpdateChildrenPositionCommand();
        $command->id = $request->attributes->get('id');

        $this->formManager->initForm($command)->handleRequest($request);

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
