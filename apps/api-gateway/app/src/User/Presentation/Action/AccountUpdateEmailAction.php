<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\User\Domain\Command\UserUpdateEmailCommand;
use App\User\Domain\Security\SecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/update-email", name="account_update_email", methods={"PATCH"})
 * @ParamConverter("command", converter="request_body")
 * @IsGranted("ROLE_USER")
 */
final class AccountUpdateEmailAction
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

    public function __invoke(UserUpdateEmailCommand $command): Response
    {
        $command->setId($this->security->getUser()->getId());

        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
