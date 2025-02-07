<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Service\ContactManager;
use Doctrine\ORM\EntityManagerInterface;
use Rakit\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/contacts', name: 'contact_')]
class ContactController extends AbstractController
{
  public function __construct(
    private ContactManager $contactManager,
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
      'email' => 'required|email',
      'phone' => 'required',
      'company_id' => 'required|integer',
    ]);

    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $company = $this->entityManager->getRepository(Companies::class)->find($data['company_id']);
      $this->contactManager->createContact(
        $data['name'],
        $data['email'],
        $data['phone'],
        $company
      );
    }
    return new JsonResponse(['message' => 'Contact created successfully'], 201);
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
      'email' => 'required|email',
      'phone' => 'required',
      'company_id' => 'required|integer',
    ]);

    if ($validation->fails()) {
      return new JsonResponse($validation->errors()->firstOfAll(), 400);
    } else {
      $company = $this->entityManager->getRepository(Companies::class)->find($data['company_id']);
      $this->contactManager->updateContact(
        $id,
        $data['name'],
        $data['email'],
        $data['phone'],
        $company
      );
    }
    return new JsonResponse(['message' => 'Contact updated successfully'], 200);
  }

  #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(int $id): void
  {
    $this->contactManager->deleteContact($id);
    return;
  }
}
