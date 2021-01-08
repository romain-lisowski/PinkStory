<?php

declare(strict_types=1);

namespace App\Mercure\Command;

use App\Validator\ValidatorManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

final class MercureCommandHandler
{
    private ParameterBagInterface $params;
    private PublisherInterface $publisher;
    private ValidatorManagerInterface $validatorManager;

    public function __construct(ParameterBagInterface $params, PublisherInterface $publisher, ValidatorManagerInterface $validatorManager)
    {
        $this->params = $params;
        $this->publisher = $publisher;
        $this->validatorManager = $validatorManager;
    }

    public function handle(MercureCommand $command): void
    {
        $this->validatorManager->validate($command);

        $topics = [$this->params->get('project_front_web_dsn').'/mercure'];
        $private = false;

        if (null !== $command->userId) {
            $topics[] = $this->params->get('project_front_web_dsn').'/users/'.$command->userId;
            $private = true;
        }

        $update = new Update(
            $topics,
            json_encode(['message' => $command->message]),
            $private
        );

        $this->publisher->__invoke($update);
    }
}
