<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Mailer;

use App\Common\Domain\Event\EventHandlerInterface;
use App\User\Domain\Event\UserCreatedEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class UserCreatedEventHandler implements EventHandlerInterface
{
    private NotifierInterface $notifier;
    private ParameterBagInterface $params;

    public function __construct(NotifierInterface $notifier, ParameterBagInterface $params)
    {
        $this->notifier = $notifier;
        $this->params = $params;
    }

    public function __invoke(UserCreatedEvent $event): void
    {
        $notification = (new Notification('Welcome', ['email']))
            ->content('Welcome on PinkStory: '.$this->params->get('project_front_web_dsn').'. Please validate your email: '.$event->getEmail().' with this code: '.$event->getEmailValidationCode())
        ;

        $this->notifier->send($notification, new Recipient($event->getEmail()));
    }
}
