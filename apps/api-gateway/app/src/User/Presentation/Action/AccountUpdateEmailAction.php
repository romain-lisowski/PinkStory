<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\User\Domain\Command\UserUpdateEmailCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/update-email", name="account_update_email", methods={"PATCH"})
 * @ParamConverter("command", converter="request_body", options={"mapping": {"id" = "security.user.id"}})
 * @IsGranted("ROLE_USER")
 */
final class AccountUpdateEmailAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(UserUpdateEmailCommand $command): Response
    {
        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
