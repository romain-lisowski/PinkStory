<?php

declare(strict_types=1);

namespace App\Form;

interface FormableInterface
{
    public function getFormType(): string;
}
