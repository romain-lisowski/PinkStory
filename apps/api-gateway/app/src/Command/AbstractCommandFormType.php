<?php

declare(strict_types=1);

namespace App\Command;

use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractCommandFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $commandFormTypeClass = (new ReflectionClass($this))->getName();
        $commandClass = preg_replace('/^(.+)FormType$/', '$1', $commandFormTypeClass);

        $resolver->setDefault('data_class', $commandClass);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
