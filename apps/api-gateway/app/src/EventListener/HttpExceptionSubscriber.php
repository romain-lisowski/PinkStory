<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\HasErrorsExceptionInterface;
use App\Responder\ExceptionResponderInterface;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\String\UnicodeString;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

final class HttpExceptionSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;
    private ExceptionResponderInterface $responder;

    public function __construct(TranslatorInterface $translator, ExceptionResponderInterface $responder)
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
        $e = $event->getThrowable();

        if (!$e instanceof HttpExceptionInterface) {
            return;
        }

        $data = [
            'type' => 'exception',
            'message' => $this->translator->trans('exception.error'),
        ];

        $this->format($e, $data);

        $response = $this->responder->render($data);
        $response->setStatusCode($e->getStatusCode());

        $event->setResponse($response);
    }

    private function format(Throwable $e, array &$data)
    {
        $data['type'] = (new UnicodeString((new ReflectionClass($e))->getShortName()))->snake()->toString();
        $data['message'] = $this->translator->trans($e->getMessage());

        if ($e instanceof HasErrorsExceptionInterface) {
            $data['errors'] = array_map([$this->translator, 'trans'], $e->getErrors());
        }

        if (null !== $e->getPrevious()) {
            $this->format($e->getPrevious(), $data);
        }
    }
}
