<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\HttpFoundation\Request;

interface FormManagerInterface
{
    public function initForm(FormableInterface $formable, array $options = []): self;

    /**
     * Handle request.
     *
     * @throws InvalidFormException
     */
    public function handleRequest(Request $request): void;
}
