<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\Story\Domain\Command\StoryCreateCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story", name="story_create", methods={"POST"})
 * @ParamConverter("command", converter="request_body", options={"mapping": {"user_id" = "security.user.id"}})
 */
final class StoryCreateAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(StoryCreateCommand $command): Response
    {
        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
