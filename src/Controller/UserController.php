<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Contracts\Cache\ItemInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("api/users")
 */
class UserController extends AbstractController
{
    private $cache;
    private $paginator;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, Serializer $serializer)
    {
        $this->cache = new FilesystemAdapter();
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    /**
     * Lists all users.
     * 
     * @OA\Response(
     *      response=200,
     *      description="Displays the list of users"
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     * @Route(name="user_list", methods={"GET"})
     */
    public function all(UserRepository $userRepository, Request $request): JsonResponse
    {
        if (0 < intval($request->query->get('page'))) {
            $page = intval($request->query->get('page'));
        } else {
            $page = 1;
        }

        $this->paginator = $userRepository->findAll();

        $response = $this->cache->get('user_collection_' . $page, function (ItemInterface $item) {
            $item->expiresAfter(1);

            return $this->serializer->serialize($this->paginator, 'json');
        });

        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Lists all users of a specific customer.
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns a user according to a customer"
     * )
     * @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="The field used to find the customer",
     *      @OA\Schema(type="int")
     * )
     * @OA\Tag(name="user")
     * @Security(name="Bearer")
     * @Route("/customer/{id}", methods={"GET"})
     */
    public function getSpecificUserByCustomer($id): JsonResponse
    {
        $this->id = intval($id);

        $response = $this->cache->get('user_item_' . $this->id, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $user =
                $this->userRepository->findByCustomer([$this->id]);

            if ($user === NULL || !is_int($this->id)) {
                throw new HttpException(404);
            }

            return $this->serializer->serialize($user, 'json');
        });

        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Lists a specific user
     * 
     * @OA\Response(
     *      response=200, 
     *      description="Displays a specific user"
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user field to find the specific user",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     * @Route("/{id}", name="get_user", methods={"GET"})
     */
    public function getSpecificUser($id): JsonResponse
    {
        $this->id = intval($id);

        $response = $this->cache->get('product_item_' . $this->id, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $user = $this->userRepository->findOneById($this->id);

            if ($user === NULL || !is_int($this->id)) {
                throw new HttpException(404);
            }

            return $this->serializer->serialize($user, 'json');
        });

        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
