<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/api/users/customer/{customerId}', name: 'api_users_get_by_customer', methods: ['GET'])]
    public function getUserForCustomer(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        $customerId
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($userRepository->findByCustomer([$customerId]), 'json', ['groups' => 'user']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/api/users/{id}', name: 'api_user_get', methods: ['GET'])]
    public function user(
        UserRepository $userRepository,
        SerializerInterface $serializer,
        $id
    ): JsonResponse {
        return new JsonResponse(
            $serializer->serialize($userRepository->findOneById([$id]), 'json', ['groups' => 'user']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
