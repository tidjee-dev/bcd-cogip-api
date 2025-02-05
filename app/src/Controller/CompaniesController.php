<?php

namespace App\Controller;

use App\Service\CompaniesManager;
use Rakit\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/companies', name: 'companies_')]
class CompaniesController extends AbstractController
{

  public function __construct(
    private CompaniesManager $companiesManager
  ) {}
  #[Route('/', name: 'index', methods: ['GET'])]
  public function index(): void
  {
    return;
  }

  #[Route('/{id}', name: 'show', methods: ['GET'])]
  public function show(int $id): JsonResponse
  {
    return new JsonResponse(compact('id'));
  }

  #[Route('/', name: 'create', methods: ['POST'])]
  public function create(
    Request $request
  ): JsonResponse {
    $data = json_decode($request->getContent(), true);

    $validator = new Validator();

    $validation = $validator->validate($data, [
      'name' => 'required',
      'type' => 'required|integer',
      'country' => 'required',
      'tva' => 'required',
    ]);

    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $this->companiesManager->createCompany(
        $data['name'],
        $data['type'],
        $data['country'],
        $data['tva']
      );
      return new JsonResponse($data);
    }
  }

  #[Route('/{id}', name: 'update', methods: ['PATCH'])]
  public function update(
    int $id,
    Request $request
  ): JsonResponse {
    $data = json_decode($request->getContent(), true);

    $validator = new Validator();

    $validation = $validator->validate($data, [
      'name' => 'required',
      'type' => 'required|integer',
      'country' => 'required',
      'tva' => 'required',
    ]);

    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $this->companiesManager->updateCompany(
        $id,
        $data['name'],
        $data['type'],
        $data['country'],
        $data['tva']
      );
      return new JsonResponse($data);
    }
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(
    int $id
  ): JsonResponse {
    $this->companiesManager->deleteCompany($id);
    return new JsonResponse(null, 204);
  }
}
