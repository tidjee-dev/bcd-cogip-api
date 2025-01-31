<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
  private UserPasswordHasherInterface $passwordHasher;
  private EntityManagerInterface $entityManager;

  public function __construct(
    UserPasswordHasherInterface $passwordHasher,
    EntityManagerInterface $entityManager
  ) {
    $this->passwordHasher = $passwordHasher;
    $this->entityManager = $entityManager;
  }

  public function createUser(
    string $email,
    string $password,
    string $firstName,
    string $lastName,
    array $roles

  ): Users {
    $user = new Users();
    $user->setEmail($email);
    $user->setPassword($this->passwordHasher->hashPassword($user, $password));
    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setRoles($roles);

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $user;
  }

  public function login(
    string $email,
    string $password
  ): JsonResponse {
    $user = $this->entityManager->getRepository(Users::class)->findOneByEmail($email);

    if (!$user) {
      return new JsonResponse(['error' => 'Invalid email!'], 400);
    }

    // TODO: Connect the user
    if (!$this->passwordHasher->isPasswordValid($user, $password)) {
      return new JsonResponse(['error' => 'Invalid password!'], 400);
    }

    return new JsonResponse(['message' => 'Login!'], 200);
  }
}
