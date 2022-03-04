<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class UserController extends AbstractController
{
    private $cache;
    private $paginator;

    public function __construct(SerializerInterface $serializer)
    {
        $this->cache = new FilesystemAdapter();
        $this->serializer = $serializer;
    }

    #[Route('/api/users/customer/{customerId}', name: 'api_users_get_by_customer', methods: ['GET'])]
    public function getUserForCustomer(UserRepository $userRepository, Request $request, $customerId): JsonResponse
    {
        if (0 < intval($request->query->get('page'))) {
            $page = intval($request->query->get('page'));
        } else {
            $page = 1;
        }

        $this->paginator = $userRepository->findByCustomer([$customerId]);

        $response = $this->cache->get('user_collection_' . $page, function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->serializer->serialize($this->paginator, 'json', ['groups' => 'user']);
        });

        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK,
            [],
            true
        );

        // $users = $this->serializer->serialize($userRepository->findByCustomer([$customerId]));

        // return new JsonResponse(
        //     $this->serializer->serialize($userRepository->findByCustomer([$customerId]), 'json', ['groups' => 'user']),
        //     JsonResponse::HTTP_OK,
        //     [],
        //     true
        // );
    }

    #[Route('/api/users/{id}', name: 'api_user_get', methods: ['GET'])]
    public function user(UserRepository $userRepository, $id): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($userRepository->findOneById([$id]), 'json', ['groups' => 'user']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
