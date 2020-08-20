<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\HasErrorsExceptionInterface;
use App\Responder\ResponderInterface;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\String\UnicodeString;
use Symfony\Contracts\Translation\TranslatorInterface;

final class HttpExceptionSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;
    private ResponderInterface $responder;

    public function __construct(TranslatorInterface $translator, ResponderInterface $responder)
    {
        $this->translator = $translator;
        $this->responder = $responder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $httpException = $event->getThrowable();

        if (!$httpException instanceof HttpExceptionInterface) {
            return;
        }

        $data = [
            'type' => 'exception',
            'message' => $this->translator->trans('exception.error'),
        ];

        $exception = $httpException->getPrevious();

        if ($exception instanceof HasErrorsExceptionInterface) {
            $data['type'] = (new UnicodeString((new ReflectionClass($exception))->getShortName()))->snake()->toString();
            $data['message'] = $this->translator->trans($exception->getMessage());
            $data['errors'] = array_map([$this->translator, 'trans'], $exception->getErrors());
        }

        if ($exception instanceof AuthenticationException) {
            $data['type'] = (new UnicodeString((new ReflectionClass($exception))->getShortName()))->snake()->toString();
            $data['message'] = $this->translator->trans($exception->getMessage());

            $childException = $exception->getPrevious();

            if (null !== $childException) {
                $data['type'] = (new UnicodeString((new ReflectionClass($childException))->getShortName()))->snake()->toString();
                $data['message'] = $this->translator->trans($childException->getMessage());

                if ($childException instanceof AccessDeniedException) {
                    $data['message'] = $this->translator->trans('exception.access_denied');
                }
            }
        }

        $response = $this->responder->render($data);
        $response->setStatusCode($httpException->getStatusCode());

        $event->setResponse($response);
    }
}
