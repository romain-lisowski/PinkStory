<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Command\CommandBusInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\User\Domain\Command\AccessTokenDeleteCommand;
use App\User\Domain\Model\AccessToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/access-token/{id<[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}>}", name="access_token_delete", methods={"DELETE"})
 * @ParamConverter("command", converter="request_body")
 * @ParamConverter("accessToken", converter="entity", options={"expr": "repository.findOne(id)"})
 * @IsGranted("ROLE_USER")
 */
final class AccessTokenDeleteAction
{
    private CommandBusInterface $commandBus;
    private ResponderInterface $responder;

    public function __construct(CommandBusInterface $commandBus, ResponderInterface $responder)
    {
        $this->commandBus = $commandBus;
        $this->responder = $responder;
    }

    public function __invoke(AccessTokenDeleteCommand $command, AccessToken $accessToken): Response
    {
        $command->setId($accessToken->getId());

        $result = $this->commandBus->dispatch($command);

        return $this->responder->render($result);
    }
}
