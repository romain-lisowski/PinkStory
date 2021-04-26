<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\EventListener;

use App\Language\Query\Repository\LanguageRepositoryInterface;
use App\User\Infrastructure\Security\SecurityInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestLanguageListener
{
    private LanguageRepositoryInterface $languageRepository;
    private SecurityInterface $security;

    public function __construct(LanguageRepositoryInterface $languageRepository, SecurityInterface $security)
    {
        $this->languageRepository = $languageRepository;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $currentLanguage = null;
        $currentReadingLanguages = [];

        // get current language from user
        if (null !== $this->security->getUser()) {
            $currentLanguage = $this->security->getUser()->getLanguage();
        }

        // get current language from locale param in uri
        if (null === $currentLanguage) {
            $currentLanguage = $this->languageRepository->findOneByLocaleForCurrent($request->query->get('_locale', 'en'), 'en');
        }

        // get current language from fallback
        if (null === $currentLanguage) {
            $currentLanguage = $this->languageRepository->findOneByLocaleForCurrent('en');
        }

        if (true === empty($currentReadingLanguages) && null !== $currentLanguage) {
            $currentReadingLanguages = [$currentLanguage];
        }

        $request->attributes->set('current-language', $currentLanguage);
        $request->attributes->set('current-reading-languages', $currentReadingLanguages);
    }
}
