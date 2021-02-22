<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\EventListener;

use App\Common\Infrastructure\Http\RequestBodyParamConversionFailedException;
use App\Common\Presentation\Http\ResponderInterface;
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class ExceptionListener
{
    private ResponderInterface $responder;

    public function __construct(ResponderInterface $responder)
    {
        $this->responder = $responder;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $statusCode = Response::HTTP_BAD_REQUEST;

        if ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();
            $e = $e->getPrevious();
        }

        if ($e instanceof RequestBodyParamConversionFailedException) {
            $e = $e->getPrevious();
        }

        if ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
        }

        if ($e instanceof UnexpectedResultException) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }

        $response = $this->responder->render(['exception' => $e]);
        $response->setStatusCode($statusCode);

        $event->setResponse($response);
    }
}
