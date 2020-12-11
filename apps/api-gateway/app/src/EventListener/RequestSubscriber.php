<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Language\Repository\Entity\LanguageRepositoryInterface;
use App\User\Security\UserSecurityManager;
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestSubscriber implements EventSubscriberInterface
{
    private LanguageRepositoryInterface $languageRepository;
    private UserSecurityManager $userSecurityManager;

    public function __construct(LanguageRepositoryInterface $languageRepository, UserSecurityManager $userSecurityManager)
    {
        $this->languageRepository = $languageRepository;
        $this->userSecurityManager = $userSecurityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $currentLanguage = null;
        $currentReadingLanguages = [];

        if (null !== $this->userSecurityManager->getUser()) {
            $currentLanguage = $this->userSecurityManager->getUser()->getLanguage();
            $currentReadingLanguages = [$currentLanguage];
        } else {
            try {
                $currentLanguage = $this->languageRepository->findOneByLocale($request->get('_locale', 'en'));
            } catch (UnexpectedResultException $e) {
                $currentLanguage = $this->languageRepository->findOneByLocale('en');
            }

            $currentReadingLanguages = [$currentLanguage];
        }

        $request->attributes->set('current-language', $currentLanguage);
        $request->setLocale($currentLanguage->getLocale());
        $request->attributes->set('current-reading-languages', $currentReadingLanguages);
    }
}
