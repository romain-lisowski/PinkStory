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
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
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
        $exception = $event->getThrowable();

        if (!$exception instanceof HttpExceptionInterface) {
            return;
        }

        $data = [];
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();

        $previous = $exception->getPrevious();

        if ($previous instanceof HasErrorsExceptionInterface) {
            $data['type'] = $nameConverter->normalize((new ReflectionClass($previous))->getShortName());
            $data['message'] = $this->translator->trans($previous->getMessage());
            $data['errors'] = array_map([$this->translator, 'trans'], $previous->getErrors());
        }

        if ($previous instanceof AuthenticationException) {
            $data['type'] = $nameConverter->normalize((new ReflectionClass($previous->getPrevious()))->getShortName());
            $data['message'] = $this->translator->trans($previous->getPrevious()->getMessage());
        }

        $response = $this->responder->render($data);
        $response->setStatusCode($exception->getStatusCode());

        $event->setResponse($response);
    }
}
