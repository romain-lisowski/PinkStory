<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Mailer;

use App\Common\Domain\Event\EventHandlerInterface;
use App\User\Domain\Event\UserRegeneratePasswordForgottenSecretEvent;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class UserRegeneratePasswordForgottenSecretEventHandler implements EventHandlerInterface
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function __invoke(UserRegeneratePasswordForgottenSecretEvent $event): void
    {
        $notification = (new Notification('Forgotten password', ['email']))
            ->content('Please update your password at this url: '.$this->params->get('project_front_web_dsn').'/password-forgotten-update/'.$event->getPasswordForgottenSecret())
        ;

        $this->notifier->send($notification, new Recipient($event->getEmail()));
    }
}
