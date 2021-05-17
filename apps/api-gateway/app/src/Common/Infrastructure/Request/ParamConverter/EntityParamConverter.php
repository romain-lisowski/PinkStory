<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Request\ParamConverter;

use App\Common\Domain\Repository\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EntityParamConverter implements ParamConverterInterface
{
    private ManagerRegistry $registry;
    private ExpressionLanguage $expressionLanguage;

    public function __construct(ManagerRegistry $registry, ExpressionLanguage $expressionLanguage)
    {
        $this->registry = $registry;
        $this->expressionLanguage = $expressionLanguage;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver, $configuration->getOptions());
            $options = $resolver->resolve($configuration->getOptions());

            $repository = $this->registry->getManagerForClass($configuration->getClass())->getRepository($configuration->getClass());

            $object = $this->expressionLanguage->evaluate($options['expr'], array_merge($request->attributes->all(), ['repository' => $repository]));

            $request->attributes->set($configuration->getName(), $object);

            return true;
        } catch (NoResultException $e) {
            throw new NotFoundHttpException(null, $e);
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }

        return false;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return null !== $configuration->getClass() && 'entity' === $configuration->getConverter();
    }

    private function configureOptions(OptionsResolver $resolver, array $options): void
    {
        $resolver->setRequired('expr');
    }
}
