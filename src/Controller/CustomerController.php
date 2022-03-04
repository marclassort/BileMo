<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/customers', name: 'api_customers_')]
class CustomerController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
    ): JsonResponse {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $serializer->serialize($user, 'json', ['groups' => 'user']),
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/delete/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        $id
    ): JsonResponse {
        $user = $userRepository->findOneById([$id]);

        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT,);
    }
}
