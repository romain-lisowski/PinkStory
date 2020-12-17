<?php

declare(strict_types=1);

namespace App\Responder;

use App\User\Security\UserSecurityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonResponder implements ResponderInterface
{
    private SerializerInterface $serializer;
    private UserSecurityManagerInterface $userSecurityManager;

    public function __construct(SerializerInterface $serializer, UserSecurityManagerInterface $userSecurityManager)
    {
        $this->serializer = $serializer;
        $this->userSecurityManager = $userSecurityManager;
    }

    public function render(array $data = [], array $context = []): Response
    {
        $data = array_merge($data, ['current-user' => $this->userSecurityManager->getCurrentUser()]);

        $context = array_merge($context, ['groups' => 'serializer']);

        $json = $this->serializer->serialize($data, JsonEncoder::FORMAT, $context);

        return new JsonResponse($json, 200, [], true);
    }
}
