<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use RuntimeException;

class UserManager
{
  public function __construct(
    private UserPasswordHasherInterface $passwordHasher,
    private EntityManagerInterface $entityManager,
    private JWTTokenManagerInterface $jwtManager
  ) {}

  public function createUser(
    string $email,
    string $password,
    string $firstname,
    string $lastname,
    array $roles = ['ROLE_USER']
  ): Users {
    $existingUser = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);
    if ($existingUser) {
      throw new \RuntimeException('Email already exists!');
    }

    $user = new Users();
    $user->setEmail($email);
    $user->setPassword($this->passwordHasher->hashPassword($user, $password));
    $user->setFirstname($firstname);
    $user->setLastname($lastname);
    $user->setRoles($roles);

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $user;
  }

  public function updateUser(
    int $id,
    string $email,
    string $password,
    string $firstname,
    string $lastname,
    array $roles = ['ROLE_USER']
  ): Users {
    $user = $this->entityManager->getRepository(Users::class)->find($id);
    if (!$user) {
      return new JsonResponse(['error' => 'User not found'], 404);
    }

    $user->setEmail($email);
    $user->setPassword($this->passwordHasher->hashPassword($user, $password));
    $user->setFirstname($firstname);
    $user->setLastname($lastname);
    $user->setRoles($roles);
    $user->setUpdatedAt(new \DateTimeImmutable());

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $user;
  }

  public function deleteUser(int $id): void
  {
    $user = $this->entityManager->getRepository(Users::class)->find($id);
    if (!$user) {
      throw new \RuntimeException('User not found');
    }
    $this->entityManager->remove($user);
    $this->entityManager->flush();
  }

  public function authenticateUser(string $email, string $password): string
  {
    $user = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);

    if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
      return new JsonResponse(['error' => 'Invalid credentials'], 401);
    }

    return $this->jwtManager->create($user);
  }
}
