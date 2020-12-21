<?php

declare(strict_types=1);

namespace App\Story\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\Story\Command\StoryCreateCommand;
use App\Story\Command\StoryCreateCommandHandler;
use App\User\Security\UserSecurityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/story/create", name="story_create_parent", methods={"POST"})
 * @Route("/story/create/{parent_id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_create_child", methods={"POST"})
 */
final class StoryCreateAction extends AbstractAction
{
    private FormManagerInterface $formManager;
    private StoryCreateCommandHandler $handler;
    private ResponderInterface $responder;
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(FormManagerInterface $formManager, StoryCreateCommandHandler $handler, ResponderInterface $responder, UserSecurityManagerInterface $userSecurityManager)
    {
        $this->formManager = $formManager;
        $this->handler = $handler;
        $this->responder = $responder;
        $this->userSecurityManager = $userSecurityManager;
    }

    public function run(Request $request): Response
    {
        $command = new StoryCreateCommand();
        $command->parentId = $request->attributes->get('parent_id') ?: null;
        $command->userId = $this->userSecurityManager->getCurrentUser()->getId();

        $this->formManager->initForm($command)->handleRequest($request);

        $command->languageId = $command->languageId ?: $request->get('current-language')->getId();

        $this->handler->setCommand($command)->handle();

        return $this->responder->render();
    }
}
