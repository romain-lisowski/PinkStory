<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\EventListener;

use App\User\Infrastructure\Security\SecurityInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestUserLanguageListener
{
    private SecurityInterface $security;

    public function __construct(SecurityInterface $security)
    {
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // get current language from user
        if (null !== $this->security->getUser()) {
            $request->attributes->set('current-language', $this->security->getUser()->getLanguage());
            $request->attributes->set('current-reading-languages', $this->security->getUser()->getReadingLanguages());
        }
    }
}
