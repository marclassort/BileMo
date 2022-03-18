<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use JMS\Serializer\SerializerInterface as JMS;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use OpenApi\Attributes as OAT;
use OpenApi\Annotations as OA;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    private $cache;
    private $paginator;
    private ProductRepository $productRepository;

    public function __construct(SerializerInterface $serializer, ProductRepository $productRepository, JMS $jms)
    {
        $this->cache = new FilesystemAdapter();
        $this->serializer = $serializer;
        $this->jms = $jms;
        $this->productRepository = $productRepository;
    }

    #[Route(name: 'product_list', methods: ['GET'])]
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

    /**     
     * @Route("/{id}", name="get_product", methods={"GET"})
     */
    public function product($id): JsonResponse
    {
        $this->id = intval($id);

        $response = $this->cache->get('product_item_' . $this->id, function (ItemInterface $item, $product) {
            $item->expiresAfter(1);
            $product = $this->productRepository->findOneById($this->id);

            if ($product === NULL || !is_int($this->id)) {
                throw new HttpException(404);
            }

            return $this->jms->serialize($product, 'json');
        });

        return new JsonResponse(
            $response,
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
