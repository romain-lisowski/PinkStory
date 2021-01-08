<?php

declare(strict_types=1);

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;

interface ResponderInterface
{
    public function render(array $data = [], array $context = []): Response;
}
