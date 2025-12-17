<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Tüm kullanıcıları getir
     */
    public function getAllUsers(): array
    {
        $users = $this->userRepository->findAll();
        return array_map(fn(User $user) => $this->formatUser($user), $users);
    }

    /**
     * ID'ye göre kullanıcı getir
     */
    public function getUserById(int $id): ?array
    {
        $user = $this->userRepository->find($id);
        return $user ? $this->formatUser($user) : null;
    }

    /**
     * Yeni kullanıcı ekle
     */
    public function createUser(string $name, string $email): array
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->formatUser($user);
    }

    /**
     * Kullanıcı güncelle
     */
    public function updateUser(int $id, string $name, string $email): ?array
    {
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            return null;
        }

        $user->setName($name);
        $user->setEmail($email);

        $this->entityManager->flush();

        return $this->formatUser($user);
    }

    /**
     * Kullanıcı sil
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            return false;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return true;
    }

    /**
     * User entity'sini array'e dönüştür
     */
    private function formatUser(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
