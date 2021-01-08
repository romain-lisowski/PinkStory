<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Domain\Message\MessageInterface;
use App\Domain\Validator\ValidatorInterface;
use App\Infrastructure\Security\SecurityInterface;
use ReflectionClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final class MessageParamConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;
    private SecurityInterface $security;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, SecurityInterface $security, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->security = $security;
        $this->validator = $validator;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver, $configuration->getOptions());
            $options = $resolver->resolve($configuration->getOptions());

            $content = json_decode($request->getContent(), true);

            foreach ($options['mapping'] as $field => $value) {
                if (true === str_contains($value, 'request.attribute.')) {
                    $content[$field] = $request->attributes->get(substr($value, strlen('request.attribute.')));
                }

                if ('security.user.id' === $value) {
                    $content[$field] = $this->security->getUser()->getId();
                }
            }

            $command = $this->serializer->deserialize(json_encode($content), $configuration->getClass(), JsonEncoder::FORMAT);

            $this->validator->validate($command);

            $request->attributes->set($configuration->getName(), $command);
        } catch (Throwable $e) {
            throw new UnprocessableEntityHttpException(null, $e);
        }
    }

    public function supports(ParamConverter $configuration)
    {
        if (null === $configuration->getClass()) {
            return false;
        }

        $class = new ReflectionClass($configuration->getClass());

        if (false === $class->implementsInterface(MessageInterface::class)) {
            return false;
        }

        return true;
    }

    private function configureOptions(OptionsResolver $resolver, array $options): void
    {
        $resolver->setDefault('mapping', function (OptionsResolver $mappingResolver) use ($options) {
            if (true === array_key_exists('mapping', $options)) {
                foreach (array_keys($options['mapping']) as $field) {
                    $mappingResolver->setDefault($field, '');
                    $mappingResolver->setAllowedTypes($field, 'string');
                    $mappingResolver->setAllowedValues($field, [
                        'request.attribute.'.$field,
                        'security.user.id',
                    ]);
                }
            }
        });
    }
}
