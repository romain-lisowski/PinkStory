<?php

declare(strict_types=1);

namespace App\Form;

use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $formTypeClass = (new ReflectionClass($this))->getName();
        $class = preg_replace('/^(.+)FormType$/', '$1', $formTypeClass);

        $resolver->setDefault('data_class', $class);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
