<?php

declare(strict_types=1);

namespace App\Mercure\Command;

use App\Exception\ValidatorException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class MercureCommandHandler
{
    private ParameterBagInterface $params;
    private PublisherInterface $publisher;
    private ValidatorInterface $validator;

    public function __construct(ParameterBagInterface $params, PublisherInterface $publisher, ValidatorInterface $validator)
    {
        $this->params = $params;
        $this->publisher = $publisher;
        $this->validator = $validator;
    }

    public function handle(MercureCommand $command): void
    {
        $errors = $this->validator->validate($command);

        if (count($errors) > 0) {
            throw new ValidatorException($errors);
        }

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
