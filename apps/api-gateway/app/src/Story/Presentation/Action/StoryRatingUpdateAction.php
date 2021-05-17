<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\Story\Domain\Command\StoryRatingUpdateCommand;
use App\Story\Domain\Model\Story;
use App\User\Domain\Security\SecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story-rating/{story_id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_rating_update", methods={"PATCH"})
 * @ParamConverter("command", converter="request_body")
 * @ParamConverter("story", converter="entity", options={"expr": "repository.findOne(story_id)"})
 * @IsGranted("ROLE_USER")
 */
final class StoryRatingUpdateAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;
    private SecurityInterface $security;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder, SecurityInterface $security)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
        $this->security = $security;
    }

    public function __invoke(StoryRatingUpdateCommand $command, Story $story): Response
    {
        $command
            ->setUserId($this->security->getUser()->getId())
            ->setStoryId($story->getId())
        ;

        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
