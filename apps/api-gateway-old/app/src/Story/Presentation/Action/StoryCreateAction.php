<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Presentation\Response\ResponderInterface;
use App\Story\Domain\Command\StoryCreateCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/story/create", name="story_create_parent", methods={"POST"})
 * @Route("/story/create/{parent_id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_create_child", methods={"POST"})
 * @ParamConverter("command", options={"mapping": {"parent_id" = "request.attribute.parent_id", "user_id" = "security.user.id"}})
 */
final class StoryCreateAction
{
    private MessageBusInterface $bus;
    private ResponderInterface $responder;

    public function __construct(MessageBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->bus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(StoryCreateCommand $command): Response
    {
        $this->bus->dispatch($command);

        return $this->responder->render();
    }
}
