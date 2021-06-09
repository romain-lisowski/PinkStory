<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\User\Domain\Command\UserCreateCommand;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Model\UserStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/signup", name="account_signup", methods={"POST"})
 * @ParamConverter("command", converter="request_data")
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

    public function __invoke(Request $request, UserCreateCommand $command): Response
    {
        $command
            ->setRole(UserRole::USER)
            ->setStatus(UserStatus::ACTIVATED)
            ->setLanguageId($request->get('current-language')->getId())
        ;

        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
