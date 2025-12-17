<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Tüm kategorileri listele
     * GET /api/categories
     */
    #[Route('', name: 'api_categories_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->findAll();
        
        return $this->json([
            'success' => true,
            'data' => array_map(fn(Category $cat) => $this->formatCategory($cat), $categories),
            'count' => count($categories),
        ]);
    }

    /**
     * Tek bir kategori getir
     * GET /api/categories/{id}
     */
    #[Route('/{id}', name: 'api_category_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return $this->json([
                'success' => false,
                'message' => 'Kategori bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'data' => $this->formatCategory($category, true),
        ]);
    }

    /**
     * Yeni kategori oluştur
     * POST /api/categories
     */
    #[Route('', name: 'api_category_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'])) {
            return $this->json([
                'success' => false,
                'message' => 'name alanı zorunludur',
            ], Response::HTTP_BAD_REQUEST);
        }

        $category = new Category();
        $category->setName($data['name']);
        $category->setDescription($data['description'] ?? null);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Kategori başarıyla oluşturuldu',
            'data' => $this->formatCategory($category),
        ], Response::HTTP_CREATED);
    }

    /**
     * Kategori güncelle
     * PUT /api/categories/{id}
     */
    #[Route('/{id}', name: 'api_category_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return $this->json([
                'success' => false,
                'message' => 'Kategori bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $category->setDescription($data['description']);
        }

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Kategori başarıyla güncellendi',
            'data' => $this->formatCategory($category),
        ]);
    }

    /**
     * Kategori sil
     * DELETE /api/categories/{id}
     */
    #[Route('/{id}', name: 'api_category_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return $this->json([
                'success' => false,
                'message' => 'Kategori bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        // Kategoriye bağlı ürünler varsa silme
        if ($category->getProducts()->count() > 0) {
            return $this->json([
                'success' => false,
                'message' => 'Bu kategoriye bağlı ürünler var, önce ürünleri silin',
            ], Response::HTTP_CONFLICT);
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Kategori başarıyla silindi',
        ]);
    }

    private function formatCategory(Category $category, bool $includeProducts = false): array
    {
        $data = [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];

        if ($includeProducts) {
            $data['products'] = array_map(fn($p) => [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'price' => $p->getPrice(),
            ], $category->getProducts()->toArray());
        }

        return $data;
    }
}
