<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Language\Model\Dto\CurrentLanguage;
use App\Language\Repository\Dto\LanguageRepositoryInterface;
use App\User\Security\UserSecurityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestSubscriber implements EventSubscriberInterface
{
    private LanguageRepositoryInterface $languageRepository;
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(LanguageRepositoryInterface $languageRepository, UserSecurityManagerInterface $userSecurityManager)
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

        if (null !== $this->userSecurityManager->getCurrentUser()) {
            $currentLanguage = $this->userSecurityManager->getCurrentUser()->getLanguage();
            $currentReadingLanguages = new ArrayCollection([$currentLanguage]);
        } else {
            try {
                $currentLanguage = $this->languageRepository->findCurrentByLocale($request->query->get('_locale', 'en'));
            } catch (UnexpectedResultException $e) {
                $currentLanguage = $this->languageRepository->findCurrentByLocale('en');
            }

            $currentReadingLanguages = new ArrayCollection([$currentLanguage]);
        }

        if ($currentLanguage instanceof CurrentLanguage) {
            $request->setLocale($currentLanguage->getLocale());
            $request->attributes->set('current-language', $currentLanguage);
            $request->attributes->set('current-reading-languages', $currentReadingLanguages);
            $request->query->remove('_locale');
        }
    }
}
