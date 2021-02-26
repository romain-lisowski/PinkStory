<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\User\Domain\Command\UserUpdatePasswordForgottenCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/update-password-forgotten", name="account_update_password_forgotten", methods={"PATCH"})
 * @ParamConverter("command", converter="request_body")
 */
final class AccountUpdatePasswordForgottenAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(UserUpdatePasswordForgottenCommand $command): Response
    {
        $this->commandBus->dispatch($command);

        return $this->responder->render();
    }
}
