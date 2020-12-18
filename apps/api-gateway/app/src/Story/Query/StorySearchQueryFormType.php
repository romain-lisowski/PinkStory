<?php

declare(strict_types=1);

namespace App\Story\Query;

use App\Form\AbstractFormType;
use App\Query\PaginableInterface;
use App\Story\Repository\Dto\StoryThemeRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class StorySearchQueryFormType extends AbstractFormType
{
    private StoryThemeRepositoryInterface $storyThemeRepository;

    public function __construct(StoryThemeRepositoryInterface $storyThemeRepository)
    {
        $this->storyThemeRepository = $storyThemeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('story_theme_ids', ChoiceType::class, [
                'property_path' => 'storyThemeIds',
                'required' => false,
                'multiple' => true,
                'choices' => $this->storyThemeRepository->findChildrenIds(),
            ])
            ->add('limit', IntegerType::class, [
                'required' => false,
                'empty_data' => strval(PaginableInterface::LIMIT),
            ])
            ->add('offset', IntegerType::class, [
                'required' => false,
                'empty_data' => strval(PaginableInterface::OFFSET),
            ])
            ->add('order', TextType::class, [
                'required' => false,
                'empty_data' => strval(StorySearchQuery::ORDER_POPULAR),
            ])
            ->add('sort', TextType::class, [
                'required' => false,
                'empty_data' => strval(Criteria::DESC),
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
