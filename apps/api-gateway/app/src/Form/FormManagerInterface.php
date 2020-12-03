<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface FormManagerInterface
{
    public function setForm(FormInterface $form): self;

    public function handleRequest(Request $request): void;
}
