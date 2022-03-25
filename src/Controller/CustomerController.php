<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

#[Route('/api/customers', name: 'api_customers_')]
class CustomerController extends AbstractController
{
    /**
     * Create a user. 
     * 
     * @OA\Response(
     *      response=201,
     *      description="Creates a user",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=User::class, groups={"user"}))
     *      )
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     */
    #[Route('/create', name: 'create', methods: ['POST'])]
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

    /**
     * Delete a specific user. 
     * 
     * @OA\Response(
     *      response=204,
     *      description="Deletes an user",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=User::class, groups={"user"}))
     *      )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The field used to find the user",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     */
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
