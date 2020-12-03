<?php

declare(strict_types=1);

namespace App\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class AbstractAction implements ActionInterface
{
    public function __invoke(Request $request): Response
    {
        try {
            return $this->run($request);
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }

    abstract public function run(Request $request): Response;
}
