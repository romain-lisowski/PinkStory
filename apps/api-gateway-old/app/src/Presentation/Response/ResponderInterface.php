<?php

declare(strict_types=1);

namespace App\Presentation\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponderInterface
{
    /**
     * @throws ResponderException
     */
    public function render(array $data = [], array $options = []): Response;
}
