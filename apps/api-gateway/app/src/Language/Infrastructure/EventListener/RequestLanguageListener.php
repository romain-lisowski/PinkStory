<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\EventListener;

use App\Language\Query\Repository\LanguageRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestLanguageListener
{
    private LanguageRepositoryInterface $languageRepository;

    public function __construct(LanguageRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $currentLanguage = null;
        $currentReadingLanguages = [];

        if (null !== $request->headers->get('Authorization') && false !== substr($request->headers->get('Authorization'), 7)) {
            // we have to use trick cause logged in user is not loaded yet
            $currentLanguage = $this->languageRepository->findOneByAccessTokenForCurrent(substr($request->headers->get('Authorization'), 7));
        }

        if (null === $currentLanguage) {
            $currentLanguage = $this->languageRepository->findOneByLocaleForCurrent($request->query->get('_locale', 'en'), 'en');
        }

        if (null !== $currentLanguage) {
            $request->setLocale($currentLanguage->getLocale());
            $currentReadingLanguages = [$currentLanguage];
        }

        $request->attributes->set('current-language', $currentLanguage);
        $request->attributes->set('current-reading-languages', new ArrayCollection($currentReadingLanguages));
    }
}
