<?php

declare(strict_types=1);

namespace App\Form;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

final class FormManager implements FormManagerInterface
{
    private FormInterface $form;

    public function setForm(FormInterface $form): self
    {
        $this->form = $form;

        return $this;
    }

    public function handleRequest(Request $request): void
    {
        $this->form->handleRequest($request);

        if (false === $this->form->isSubmitted()) {
            throw new NotSubmittedFormException();
        }

        if (false === $this->form->isValid()) {
            throw new InvalidFormException($this->form->getErrors(true));
        }
    }
}
