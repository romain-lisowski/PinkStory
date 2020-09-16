<?php

declare(strict_types=1);

namespace App\User\Mailer\Message\Handler;

use App\User\Message\UserSignupMessage;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class UserSignupMessageHandler implements MessageSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function __invoke(UserSignupMessage $message)
    {
        $user = $this->repository->findOne($message->getId());

        // TODO: send mail
    }

    public static function getHandledMessages(): iterable
    {
        yield UserSignupMessage::class => [
            'from_transport' => 'sync',
        ];
    }
}
