<?php

namespace App\Controller;

use App\Entity\Types;
use App\Service\CompaniesManager;
use Doctrine\ORM\EntityManagerInterface;
use Rakit\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/companies', name: 'companies_')]
class CompaniesController extends AbstractController
{

  public function __construct(
    private CompaniesManager $companiesManager,
    private EntityManagerInterface $entityManager
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
      $type = $this->entityManager->getRepository(Types::class)->find($data['type']);

      $this->companiesManager->createCompany(
        $data['name'],
        $type,
        $data['country'],
        $data['tva']
      );
      return new JsonResponse(['message' => 'Company created successfully'], 201);
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
      'name' => 'required',
      'type' => 'required|integer',
      'country' => 'required',
      'tva' => 'required',
    ]);

    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $type = $this->entityManager->getRepository(Types::class)->find($data['type']);
      // dd($type);
      $this->companiesManager->updateCompany(
        $id,
        $data['name'],
        $type,
        $data['country'],
        $data['tva']
      );
      return new JsonResponse(['message' => 'Company updated successfully'], 200);
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
