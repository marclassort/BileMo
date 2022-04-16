<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface as Serializer;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/products")
 */
class ProductController extends AbstractController
{
    private $cache;
    private $paginator;
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository, Serializer $serializer)
    {
        $this->cache = new FilesystemAdapter();
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
    }

    /**
     * Lists all phone products. 
     * 
     * @OA\Response(
     *      response=200,
     *      description="Displays the list of phone products"
     * )
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     * @Route(name="product_list", methods={"GET"})
     */
    public function all(ProductRepository $productRepository, Request $request): JsonResponse
    {
        if (0 < intval($request->query->get('page'))) {
            $page = intval($request->query->get('page'));
        } else {
            $page = 1;
        }

        $this->paginator = $productRepository->getPaginatedProducts($page);

        if ($this->paginator->getQuery() === []) {
            return new JsonResponse(
                null,
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        $response = $this->cache->get('product_collection_' . $page, function (ItemInterface $item) {
            $item->expiresAfter(3600);

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
     * Lists a specific product. 
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns a product"
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The field used to find the product",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="product")
     * @Security(name="Bearer")
     * @Route("/{id}", name="get_product", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function product($id): JsonResponse
    {
        $this->id = intval($id);

        $response = $this->cache->get('product_item_' . $this->id, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $product = $this->productRepository->findOneById($this->id);

            if ($product === NULL || !is_int($this->id)) {
                throw new HttpException(404);
            }

            return $this->serializer->serialize($product, 'json');
        });

        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
