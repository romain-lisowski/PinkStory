<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Mailer;

use App\Common\Domain\Event\EventHandlerInterface;
use App\User\Domain\Event\UserUpdatedEmailEvent;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class UserUpdatedEmailEventHandler implements EventHandlerInterface
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function __invoke(UserUpdatedEmailEvent $event): void
    {
        $notification = (new Notification('Email updated', ['email']))
            ->content('Please validate your new email: '.$event->getEmail().' with this code: '.$event->getEmailValidationCode())
        ;

        $this->notifier->send($notification, new Recipient($event->getEmail()));
    }
}
