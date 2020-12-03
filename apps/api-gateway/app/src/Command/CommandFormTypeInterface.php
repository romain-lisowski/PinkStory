<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface CommandFormTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void;

    public function configureOptions(OptionsResolver $resolver): void;

    public function getBlockPrefix(): string;
}
