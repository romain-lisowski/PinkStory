<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Form\AbstractFormType;
use App\Query\PaginableInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class StoryImageSearchQueryFormType extends AbstractFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('story_theme_ids', CollectionType::class, [
                'property_path' => 'storyThemeIds',
                'entry_type' => TextType::class,
                'required' => false,
                'allow_add' => true,
            ])
            ->add('limit', IntegerType::class, [
                'required' => false,
                'empty_data' => strval(PaginableInterface::LIMIT),
            ])
            ->add('offset', IntegerType::class, [
                'required' => false,
                'empty_data' => strval(PaginableInterface::OFFSET),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'method' => Request::METHOD_GET,
        ]);
    }
}
