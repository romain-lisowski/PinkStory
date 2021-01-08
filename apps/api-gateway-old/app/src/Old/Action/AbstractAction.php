<?php

declare(strict_types=1);

namespace App\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

abstract class AbstractAction implements ActionInterface
{
    public function __invoke(Request $request): Response
    {
        try {
            return $this->run($request);
        } catch (AccessDeniedException $e) {
            throw new AccessDeniedHttpException(null, $e);
        } catch (AuthenticationException $e) {
            throw new UnauthorizedHttpException('Bearer realm="'.$request->getUri().'"', null, (null !== $e->getPrevious() ? $e->getPrevious() : $e));
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }

    abstract public function run(Request $request): Response;
}
