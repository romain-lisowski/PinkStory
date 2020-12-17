<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\Story\Command\StoryRatingUpdateCommand;
use App\Story\Command\StoryRatingUpdateCommandHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/story-rating/update/{story_id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_rating_update", methods={"PATCH"})
 */
final class StoryRatingUpdateAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserSecurityManagerInterface $userSecurityManager;
    private StoryRatingUpdateCommandHandler $handler;

    public function __construct(FormManagerInterface $formManager, ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager, StoryRatingUpdateCommandHandler $handler)
    {
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new StoryRatingUpdateCommand();
        $command->storyId = (string) $request->attributes->get('story_id');
        $command->userId = $this->userSecurityManager->getCurrentUser()->getId();

        $this->formManager->initForm($command)->handleRequest($request);

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
