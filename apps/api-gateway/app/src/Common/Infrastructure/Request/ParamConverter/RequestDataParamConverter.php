<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\UnicodeString;

final class RequestDataParamConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            $bodyData = false === empty($request->getContent()) ? json_decode($request->getContent(), true) : [];

            $queryData = $request->query->all();
            $this->typeParams($configuration->getClass(), $queryData);

            $data = array_merge($bodyData, $queryData);

            $object = $this->serializer->deserialize(json_encode($data), $configuration->getClass(), JsonEncoder::FORMAT);

            $request->attributes->set($configuration->getName(), $object);

            return true;
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }

        return false;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return null !== $configuration->getClass() && 'request_data' === $configuration->getConverter();
    }

    private function typeParams(string $class, array &$data): void
    {
        $reflection = new \ReflectionClass($class);

        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType()->getName();
            $property = (new UnicodeString($property->getName()))->snake()->toString();

            foreach ($data as $key => $value) {
                if ($property === $key) {
                    if ('bool' === $type) {
                        $data[$key] = boolval($value);
                    } elseif ('int' === $type) {
                        $data[$key] = intval($value);
                    } elseif ('float' === $type) {
                        $data[$key] = floatval($value);
                    }

                    break;
                }
            }
        }
    }
}
