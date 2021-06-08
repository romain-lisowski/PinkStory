<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\User\Domain\Command\UserRegenerateEmailValidationCodeCommand;
use App\User\Domain\Security\SecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/regenerate-email-validation-code", name="account_regenerate_email_validation_code", methods={"PATCH"})
 * @ParamConverter("command", converter="request_data")
 * @IsGranted("ROLE_USER")
 */
final class AccountRegenerateEmailValidationCodeAction
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

    public function __invoke(UserRegenerateEmailValidationCodeCommand $command): Response
    {
        $command->setId($this->security->getUser()->getId());

        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
