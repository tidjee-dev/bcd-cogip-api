<?php

namespace App\Controller;

use App\Service\InvoicesManager;
use Rakit\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/invoices', name: 'invoices_')]
class InvoicesController extends AbstractController
{

  public function __construct(
    private InvoicesManager $invoicesManager
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
      $this->invoicesManager->createInvoice(
        $data['ref'],
        $data['company_id']
      );
    }
    return new JsonResponse(['message' => 'Invoice created successfully'], 201);
  }

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
      $this->invoicesManager->updateInvoice(
        $id,
        $data['ref'],
        $data['company_id']
      );
      return new JsonResponse(['message' => 'Invoice updated successfully']);
    }
  }

  public function delete(int $id): JsonResponse
  {
    $this->invoicesManager->deleteInvoice($id);
    return new JsonResponse(['message' => 'Invoice deleted successfully']);
  }
}
