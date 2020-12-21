<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\Story\Command\StoryDeleteCommand;
use App\Story\Command\StoryDeleteCommandHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/story/delete/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_delete", methods={"DELETE"})
 */
final class StoryDeleteAction extends AbstractAction
{
    private ResponderInterface $responder;
    private UserSecurityManagerInterface $userSecurityManager;
    private StoryDeleteCommandHandler $handler;

    public function __construct(ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager, StoryDeleteCommandHandler $handler)
    {
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new StoryDeleteCommand();
        $command->id = (string) $request->attributes->get('id');

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
