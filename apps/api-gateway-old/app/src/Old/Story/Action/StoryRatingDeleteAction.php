<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\Story\Command\StoryRatingDeleteCommand;
use App\Story\Command\StoryRatingDeleteCommandHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/story-rating/delete/{story_id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_rating_delete", methods={"DELETE"})
 */
final class StoryRatingDeleteAction extends AbstractAction
{
    private ResponderInterface $responder;
    private UserSecurityManagerInterface $userSecurityManager;
    private StoryRatingDeleteCommandHandler $handler;

    public function __construct(ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager, StoryRatingDeleteCommandHandler $handler)
    {
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new StoryRatingDeleteCommand();
        $command->storyId = (string) $request->attributes->get('story_id');
        $command->userId = $this->userSecurityManager->getCurrentUser()->getId();

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
