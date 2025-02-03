<?php


namespace App\Controller;

use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
  #[Route('/api/login', name: 'app_login', methods: ['POST'])]
  public function login(Request $request, UserManager $userManager): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    if (!isset($data['email']) || !isset($data['password'])) {
      return new JsonResponse(['error' => 'Email and password required'], 400);
    }

    return $userManager->authenticateUser($data['email'], $data['password']);
  }

  #[Route('/api/register', name: 'app_register', methods: ['POST'])]
  public function register(Request $request, UserManager $userManager): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $requiredFields = ['email', 'password', 'firstname', 'lastname'];
    foreach ($requiredFields as $field) {
      if (empty($data[$field])) {
        return new JsonResponse(['error' => "Field $field is required"], 400);
      }
    }

    try {
      $userManager->createUser(
        $data['email'],
        $data['password'],
        $data['firstname'],
        $data['lastname']
      );
      return new JsonResponse(['message' => 'User created successfully'], 201);
    } catch (\RuntimeException $e) {
      return new JsonResponse(['error' => $e->getMessage()], 400);
    }
  }
}
