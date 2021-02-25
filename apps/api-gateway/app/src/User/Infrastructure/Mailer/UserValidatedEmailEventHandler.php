<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Mailer;

use App\Common\Domain\Event\EventHandlerInterface;
use App\User\Domain\Event\UserValidatedEmailEvent;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class UserValidatedEmailEventHandler implements EventHandlerInterface
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function __invoke(UserValidatedEmailEvent $event): void
    {
        $notification = (new Notification('Email validated', ['email']))
            ->content('Your email: '.$event->getEmail().' has been validated.')
        ;

        $this->notifier->send($notification, new Recipient($event->getEmail()));
    }
}
