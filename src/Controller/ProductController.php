<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function home(ProductRepository $productRepository): Response
    {
        return $this->json($productRepository->findAll(), 200, [], ['groups' => 'group1']);
    }

    #[Route('/product', name: 'product', methods: ['GET', 'POST'])]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->json([
            'message' => 'Welcome to product page!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    #[Route('/', name: 'api_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonReceived = $request->getContent();

        try {
            $product = $serializer->deserialize($jsonReceived, Product::class, 'json');

            $errors = $validator->validate($product);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($product);
            $em->flush();

            return $this->json($product, 201, [], ['groups' => 'group1']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
