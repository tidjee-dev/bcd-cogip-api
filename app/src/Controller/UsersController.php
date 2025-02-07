<?php

namespace App\Controller;

use Rakit\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/users', name: 'users_')]
// #[IsGranted('ROLE_ADMIN')]
class UsersController extends AbstractController
{
  public function __construct(
    private UserManager $userManager
  ) {}

  #[Route('/', name: 'index', methods: ['GET'])]
  public function index(): void
  {
    return;
  }

  #[Route('/{id}', name: 'show', methods: ['GET'])]
  public function show(int $id): JsonResponse
  {
    return new JsonResponse(['id' => $id]);
  }


  #[Route('/', name: 'create', methods: ['POST'])]
  public function create(
    Request $request
  ): JsonResponse {
    // Retrieve the request data
    $data = json_decode($request->getContent(), true);

    // Validate the request data
    $validator = new Validator();

    $validation = $validator->validate($data, [
      'firstname' => 'required',
      'lastname' => 'required',
      'email' => 'required|email',
      'password' => 'required|min:6',
      'roles' => 'array',
      'roles.*' => 'in:ROLE_USER,ROLE_ADMIN'
    ]);

    if ($validation->fails()) {
      $errors = $validation->errors();
      return new JsonResponse($errors->firstOfAll(), Response::HTTP_BAD_REQUEST);
    } else {
      try {
        // Header: Content-Type: application/Id+json
        header('Content-Type: application/Id+json');

        // Sanitize the request data
        $data = htmlspecialchars($data);

        // Create a new user
        $this->userManager->createUser(
          $data['email'],
          $data['password'],
          $data['firstname'],
          $data['lastname'],
          $data['roles'] ?? ['ROLE_USER']
        );
        return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
      } catch (\RuntimeException $e) {
        return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
      }
    }
  }

  #[Route('/{id}', name: 'update', methods: ['PUT'])]
  public function update(
    int $id,
    Request $request
  ): JsonResponse {
    $data = json_decode($request->getContent(), true);
    $validator = new Validator();
    $validation = $validator->validate($data, [
      'firstname' => 'required',
      'lastname' => 'required',
      'email' => 'required|email',
      'password' => 'required|min:6',
      'roles' => 'array',
      'roles.*' => 'in:ROLE_USER,ROLE_ADMIN'
    ]);

    if ($validation->fails()) {
      $errors = $validation->errors();
      return new JsonResponse($errors->firstOfAll(), Response::HTTP_BAD_REQUEST);
    } else {
      try {
        header('Content-Type: application/merge-patch+json');

        $this->userManager->updateUser(
          $id,
          $data['email'],
          $data['password'],
          $data['firstname'],
          $data['lastname'],
          $data['roles'] ?? ['ROLE_USER']
        );
        return new JsonResponse(['message' => 'User updated successfully'], Response::HTTP_OK);
      } catch (\RuntimeException $e) {
        return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
      }
    }
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(int $id): JsonResponse
  {
    $validator = new Validator();
    $validation = $validator->validate(['id' => $id], [
      'id' => 'required|numeric'
    ]);

    if ($validation->fails()) {
      $errors = $validation->errors();
      return new JsonResponse($errors->firstOfAll(), Response::HTTP_BAD_REQUEST);
    } else {
      // Check if id = current_user_id
      if ($id === $this->getUser()) {
        return new JsonResponse(['error' => 'You cannot delete yourself'], Response::HTTP_FORBIDDEN);
      }

      $this->userManager->deleteUser($id);
      return new JsonResponse(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }
  }
}
