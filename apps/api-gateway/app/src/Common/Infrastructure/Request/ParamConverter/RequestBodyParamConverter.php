<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final class RequestBodyParamConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            $object = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), JsonEncoder::FORMAT);

            $request->attributes->set($configuration->getName(), $object);

            return true;
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }

        return false;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return null !== $configuration->getClass() && 'request_body' === $configuration->getConverter();
    }
}
