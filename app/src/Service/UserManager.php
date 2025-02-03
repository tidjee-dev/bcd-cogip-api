<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

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
    string $firstName,
    string $lastName,
    array $roles = ['ROLE_USER']
  ): Users {
    $existingUser = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);
    if ($existingUser) {
      throw new \RuntimeException('Email already exists!');
    }

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

  public function authenticateUser(string $email, string $password): JsonResponse
  {
    $user = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);

    if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
      return new JsonResponse(['error' => 'Invalid credentials'], 401);
    }

    $token = $this->jwtManager->create($user);
    return new JsonResponse(['token' => $token], 200);
  }
}
