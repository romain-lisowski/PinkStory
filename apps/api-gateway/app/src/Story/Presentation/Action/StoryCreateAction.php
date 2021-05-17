<?php

declare(strict_types=1);

namespace App\Story\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\Story\Domain\Command\StoryCreateCommand;
use App\User\Domain\Security\SecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/story", name="story_create", methods={"POST"})
 * @ParamConverter("command", converter="request_body")
 * @IsGranted("ROLE_USER")
 */
final class StoryCreateAction
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

    public function __invoke(StoryCreateCommand $command): Response
    {
        $command->setUserId($this->security->getUser()->getId());

        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
