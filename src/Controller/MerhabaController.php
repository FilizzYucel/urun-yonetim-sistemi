<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MerhabaController extends AbstractController
{
    private UserService $userService;

    // Constructor - Dependency Injection
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/merhaba', name: 'app_merhaba')]
    public function index(): Response
    {
        return new Response('Merhaba Filiz, Symfony çalışıyor!');
    }

    /**
     * Tüm kullanıcıları listele
     * GET /users
     */
    #[Route('/users', name: 'app_users_list', methods: ['GET'])]
    public function listUsers(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return $this->json([
            'success' => true,
            'data' => $users,
            'count' => count($users),
        ]);
    }

    /**
     * Tek bir kullanıcı getir
     * GET /users/{id}
     */
    #[Route('/users/{id}', name: 'app_user_show', methods: ['GET'])]
    public function showUser(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);
        
        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Yeni kullanıcı oluştur
     * POST /users
     */
    #[Route('/users', name: 'app_user_create', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation
        if (!isset($data['name']) || !isset($data['email'])) {
            return $this->json([
                'success' => false,
                'message' => 'name ve email alanları zorunludur',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json([
                'success' => false,
                'message' => 'Geçerli bir email adresi giriniz',
            ], Response::HTTP_BAD_REQUEST);
        }

        $newUser = $this->userService->createUser($data['name'], $data['email']);

        return $this->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla oluşturuldu',
            'data' => $newUser,
        ], Response::HTTP_CREATED);
    }

    /**
     * Kullanıcı güncelle
     * PUT /users/{id}
     */
    #[Route('/users/{id}', name: 'app_user_update', methods: ['PUT'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['email'])) {
            return $this->json([
                'success' => false,
                'message' => 'name ve email alanları zorunludur',
            ], Response::HTTP_BAD_REQUEST);
        }

        $updatedUser = $this->userService->updateUser($id, $data['name'], $data['email']);

        if (!$updatedUser) {
            return $this->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla güncellendi',
            'data' => $updatedUser,
        ]);
    }

    /**
     * Kullanıcı sil
     * DELETE /users/{id}
     */
    #[Route('/users/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function deleteUser(int $id): JsonResponse
    {
        $deleted = $this->userService->deleteUser($id);

        if (!$deleted) {
            return $this->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla silindi',
        ]);
    }
}




