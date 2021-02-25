<?php

declare(strict_types=1);

namespace App\Common\Presentation\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponderInterface
{
    public function render(array $data = [], array $options = []): Response;
}
