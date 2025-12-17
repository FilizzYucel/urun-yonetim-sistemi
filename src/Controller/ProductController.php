<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Tüm ürünleri listele
     * GET /api/products
     */
    #[Route('', name: 'api_products_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        // Kategori filtreleme
        $categoryId = $request->query->get('category');
        
        if ($categoryId) {
            $products = $this->productRepository->findBy(['category' => $categoryId]);
        } else {
            $products = $this->productRepository->findAll();
        }
        
        return $this->json([
            'success' => true,
            'data' => array_map(fn(Product $p) => $this->formatProduct($p), $products),
            'count' => count($products),
        ]);
    }

    /**
     * Tek bir ürün getir
     * GET /api/products/{id}
     */
    #[Route('/{id}', name: 'api_product_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            return $this->json([
                'success' => false,
                'message' => 'Ürün bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'data' => $this->formatProduct($product),
        ]);
    }

    /**
     * Yeni ürün oluştur
     * POST /api/products
     */
    #[Route('', name: 'api_product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation
        $required = ['name', 'price', 'category_id'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->json([
                    'success' => false,
                    'message' => "$field alanı zorunludur",
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        // Kategori kontrolü
        $category = $this->categoryRepository->find($data['category_id']);
        if (!$category) {
            return $this->json([
                'success' => false,
                'message' => 'Kategori bulunamadı',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Fiyat kontrolü
        if (!is_numeric($data['price']) || $data['price'] < 0) {
            return $this->json([
                'success' => false,
                'message' => 'Geçerli bir fiyat giriniz',
            ], Response::HTTP_BAD_REQUEST);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setDescription($data['description'] ?? null);
        $product->setPrice((string) $data['price']);
        $product->setStock($data['stock'] ?? 0);
        $product->setCategory($category);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Ürün başarıyla oluşturuldu',
            'data' => $this->formatProduct($product),
        ], Response::HTTP_CREATED);
    }

    /**
     * Ürün güncelle
     * PUT /api/products/{id}
     */
    #[Route('/{id}', name: 'api_product_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            return $this->json([
                'success' => false,
                'message' => 'Ürün bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $product->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $product->setDescription($data['description']);
        }
        if (isset($data['price'])) {
            if (!is_numeric($data['price']) || $data['price'] < 0) {
                return $this->json([
                    'success' => false,
                    'message' => 'Geçerli bir fiyat giriniz',
                ], Response::HTTP_BAD_REQUEST);
            }
            $product->setPrice((string) $data['price']);
        }
        if (isset($data['stock'])) {
            $product->setStock((int) $data['stock']);
        }
        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->find($data['category_id']);
            if (!$category) {
                return $this->json([
                    'success' => false,
                    'message' => 'Kategori bulunamadı',
                ], Response::HTTP_BAD_REQUEST);
            }
            $product->setCategory($category);
        }

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Ürün başarıyla güncellendi',
            'data' => $this->formatProduct($product),
        ]);
    }

    /**
     * Ürün sil
     * DELETE /api/products/{id}
     */
    #[Route('/{id}', name: 'api_product_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            return $this->json([
                'success' => false,
                'message' => 'Ürün bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Ürün başarıyla silindi',
        ]);
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'price' => $product->getPrice(),
            'stock' => $product->getStock(),
            'category' => [
                'id' => $product->getCategory()?->getId(),
                'name' => $product->getCategory()?->getName(),
            ],
            'createdAt' => $product->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
