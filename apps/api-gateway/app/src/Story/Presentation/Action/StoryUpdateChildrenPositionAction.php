<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\Story\Domain\Command\StoryUpdateChildrenPositionCommand;
use App\Story\Domain\Model\Story;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story/update-children-position/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="story_update_children_position", methods={"PATCH"})
 * @ParamConverter("command", converter="request_body")
 * @Entity("story", expr="repository.findOne(id)")
 * @IsGranted("ROLE_USER")
 */
final class StoryUpdateChildrenPositionAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(StoryUpdateChildrenPositionCommand $command, Story $story): Response
    {
        $command->setId($story->getId());

        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
