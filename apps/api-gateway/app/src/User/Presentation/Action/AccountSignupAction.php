<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Http\ResponderInterface;
use App\User\Domain\Command\UserCreateCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/signup", name="account_signup", methods={"POST"})
 * @ParamConverter("command", converter="request_body", options={"mapping": {"role" = "enum.App\User\Domain\Model\UserRole::USER", "status" = "enum.App\User\Domain\Model\UserStatus::ACTIVATED"}})
 */
final class AccountSignupAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(UserCreateCommand $command): Response
    {
        $this->commandBus->dispatch($command);

        return $this->responder->render();
    }
}
