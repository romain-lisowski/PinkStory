<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Mailer;

use App\Common\Domain\Event\EventHandlerInterface;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class UserCreatedEventHandler implements EventHandlerInterface
{
    private NotifierInterface $notifier;
    private ParameterBagInterface $params;
    private UserRepositoryInterface $userRepository;

    public function __construct(NotifierInterface $notifier, ParameterBagInterface $params, UserRepositoryInterface $userRepository)
    {
        $this->notifier = $notifier;
        $this->params = $params;
        $this->userRepository = $userRepository;
    }

    public function __invoke(UserCreatedEvent $event): void
    {
        $user = $this->userRepository->findOne($event->getId());

        $notification = (new Notification('Welcome', ['email']))
            ->content('Welcome on PinkStory: '.$this->params->get('project_front_web_dsn').'. Please validate your email: '.$user->getEmail())
        ;

        $this->notifier->send($notification, new Recipient($user->getEmail()));
    }
}
