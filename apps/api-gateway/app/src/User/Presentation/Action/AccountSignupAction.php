<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Presentation\Http\ResponderInterface;
use App\User\Domain\Command\UserCreateCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/signup", name="account_signup", methods={"POST"})
 */
final class AccountSignupAction
{
    private MessageBusInterface $bus;
    private ResponderInterface $responder;

    public function __construct(MessageBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->bus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(UserCreateCommand $command): Response
    {
        $this->bus->dispatch($command);

        return $this->responder->render();
    }
}
