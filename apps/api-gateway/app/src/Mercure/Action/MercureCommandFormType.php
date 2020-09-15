<?php

declare(strict_types=1);

namespace App\Mercure\Action;

use App\Mercure\Command\MercureCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MercureCommandFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextType::class)
            ->add('user_id', TextType::class, [
                'property_path' => 'userId',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MercureCommand::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
