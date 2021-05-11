<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\Story\Domain\Command\StoryRatingDeleteCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story-rating/{story_id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_rating_delete", methods={"DELETE"})
 * @ParamConverter("command", converter="request_body", options={"mapping": {"story_id" = "request.attribute.story_id", "user_id" = "security.user.id"}})
 * @IsGranted("ROLE_USER")
 */
final class StoryRatingDeleteAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(StoryRatingDeleteCommand $command): Response
    {
        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
