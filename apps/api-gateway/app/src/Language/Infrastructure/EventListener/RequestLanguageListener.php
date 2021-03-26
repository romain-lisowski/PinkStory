<?php

declare(strict_types=1);

namespace App\Language\Infrastructure\EventListener;

use App\Common\Domain\Repository\NoResultException;
use App\Language\Domain\Repository\LanguageRepositoryInterface;
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

        try {
            $currentLanguage = $this->languageRepository->findOneByLocale($request->query->get('_locale', 'en'));
        } catch (NoResultException $e) {
            $currentLanguage = $this->languageRepository->findOneByLocale('en');
        }

        $currentReadingLanguages = [$currentLanguage];

        $request->attributes->set('current-language', $currentLanguage);
        $request->attributes->set('current-reading-languages', $currentReadingLanguages);
    }
}
