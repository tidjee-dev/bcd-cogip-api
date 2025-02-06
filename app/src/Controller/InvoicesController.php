<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Service\InvoicesManager;
use Doctrine\ORM\EntityManagerInterface;
use Rakit\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/invoices', name: 'invoices_')]
class InvoicesController extends AbstractController
{

  public function __construct(
    private InvoicesManager $invoicesManager,
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
  public function create(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $validator = new Validator();

    $validation = $validator->validate($data, [
      'ref' => 'required',
      'company_id' => 'required|integer',
    ]);
    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $company = $this->entityManager->getRepository(Companies::class)->find($data['company_id']);
      $this->invoicesManager->createInvoice(
        $data['ref'],
        $company
      );
    }
    return new JsonResponse(['message' => 'Invoice created successfully'], 201);
  }

  #[Route('/{id}', name: 'update', methods: ['PUT'])]
  public function update(
    int $id,
    Request $request
  ): JsonResponse {
    $data = json_decode($request->getContent(), true);

    $validator = new Validator();

    $validation = $validator->validate($data, [
      'ref' => 'required',
      'company_id' => 'required|integer',
    ]);

    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $company = $this->entityManager->getRepository(Companies::class)->find($data['company_id']);

      if (!$company) {
        return new JsonResponse(['message' => 'Company not found'], 404);
      }

      try {
        $this->invoicesManager->updateInvoice(
          $id,
          $data['ref'],
          $company
        );
        return new JsonResponse(['message' => 'Invoice updated successfully']);
      } catch (\RuntimeException $e) {
        return new JsonResponse(['message' => $e->getMessage()], 404);
      }
    }
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(int $id): JsonResponse
  {
    $this->invoicesManager->deleteInvoice($id);
    return new JsonResponse(['message' => 'Invoice deleted successfully']);
  }
}
