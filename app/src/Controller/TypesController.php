<?php

namespace App\Controller;

use App\Service\TypesManager;
use Rakit\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/types', name: 'types_')]
class TypesController extends AbstractController
{
  public function __construct(
    private TypesManager $typesManager
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
  public function create(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $validator = new Validator();

    $validation = $validator->validate($data, [
      'name' => 'required',
    ]);
    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      return new JsonResponse($data);
    }
  }

  #[Route('/{id}', name: 'update', methods: ['PUT'])]
  public function update(int $id, Request $request)
  {
    $data = json_decode($request->getContent(), true);

    $validator = new Validator();

    $validation = $validator->validate($data, [
      'name' => 'required',
    ]);
    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      header('Content-Type: application/merge-patch+json');

      $this->typesManager->updateType($id, $data['name']);

      return new JsonResponse($data);
    }
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(
    int $id
  ) {
    $validator = new Validator();

    $validation = $validator->validate(['id' => $id], [
      'id' => 'required|numeric',
    ]);

    if($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $this->typesManager->deleteType($id);

      return new JsonResponse(null, 204);
    }
  }
}
