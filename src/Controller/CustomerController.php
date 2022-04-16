<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface as Serializer;
use OpenApi\Annotations as OA;

/**
 * @Route("api/customers")
 */
class CustomerController extends AbstractController
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository, Serializer $serializer)
    {
        $this->customerRepository = $customerRepository;
        $this->serializer = $serializer;
    }

    /**
     * Creates a user. 
     * 
     * @OA\Response(
     *      response=201,
     *      description="Creates a user"
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            $this->serializer->serialize($user, 'json', ['groups' => 'user']),
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * Deletes a specific user. 
     * 
     * @OA\Response(
     *      response=204,
     *      description="Deletes an user"
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The field used to find the user",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     * @Route("/delete/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
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
