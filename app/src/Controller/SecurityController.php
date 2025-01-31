<?php

namespace App\Controller;

use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
  private UserManager $userManager;

  public function __construct(UserManager $userManager)
  {
    $this->userManager = $userManager;
  }

  #[Route('/api/login', name: 'app_login', methods: ['POST'])]
  public function login(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $email = $data['email'];
    $password = $data['password'];

    if (!$email || !$password) {
      return new JsonResponse(['error' => 'Invalid data!'], 400);
    } else {
      $this->userManager->login($email, $password);
    }

    return new JsonResponse(['message' => 'Login!'], 200);
  }

  #[Route('/api/register', name: 'app_register', methods: ['POST'])]
  public function register(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $roles = ['ROLE_USER'];

    $this->userManager->createUser(
      $data['email'],
      $data['password'],
      $data['first_name'],
      $data['last_name'],
      $roles
    );

    if (!$data['email'] || !$data['password'] || !$data['first_name'] || !$data['last_name']) {
      return new JsonResponse(['error' => 'Invalid data!'], 400);
    }

    return new JsonResponse(['message' => 'User registered!'], 201);
  }
}
