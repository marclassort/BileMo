<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

#[Route('/api/products', name: 'api_products_')]
class ProductController extends AbstractController
{
    private $cache;
    private $paginator;

    public function __construct(SerializerInterface $serializer)
    {
        $this->cache = new FilesystemAdapter();
        $this->serializer = $serializer;
    }

    #[OA\Response(
        response: 200,
        description: 'Returns all products'
    )]
    #[Route('', name: 'all', methods: ['GET'])]
    public function all(ProductRepository $productRepository, Request $request): JsonResponse
    {
        if (0 < intval($request->query->get('page'))) {
            $page = intval($request->query->get('page'));
        } else {
            $page = 1;
        }

        $this->paginator = $productRepository->getPaginatedProducts($page);

        $response = $this->cache->get('product_collection_' . $page, function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->serializer->serialize($this->paginator, 'json', ['groups' => 'product']);
        });

        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    #[OA\Response(
        response: 200,
        description: 'Returns a specific product'
    )]
    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function product($id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->findOneById($id);

        $response = $this->cache->get('product_test_' . $product->getId(), function (ItemInterface $item, $product) {
            $item->expiresAfter(3600);

            if (!$product) {
                throw new HttpException(404);
            }

            return $this->serializer->serialize($product, 'json');
        });

        if (!isset($product) or $product === NULL) {
            $message = "Aucun contenu n'a été trouvé.";

            $response = new JsonResponse();

            $response->setContent($message);

            $response->setStatusCode(JsonResponse::HTTP_NO_CONTENT);

            return $response;
        } else {
            return new JsonResponse(
                $response,
                JsonResponse::HTTP_OK,
                [],
                true
            );
        }
    }
}
