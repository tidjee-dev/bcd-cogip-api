<?php

namespace App\Service;

use App\Entity\Companies;
use App\Entity\Contacts;
use Doctrine\ORM\EntityManagerInterface;

class ContactManager
{
  public function __construct(
    private EntityManagerInterface $entityManager
  ) {}

  public function createContact(
    string $name,
    string $email,
    string $phone,
    Companies $company
  ): Contacts {
    $contact = new Contacts();
    $contact->setName($name);
    $contact->setEmail($email);
    $contact->setPhone($phone);
    $contact->setCompany($company);

    $this->entityManager->persist($contact);
    $this->entityManager->flush();

    return $contact;
  }

  public function updateContact(
    int $id,
    string $name,
    string $email,
    string $phone,
    Companies $company
  ): Contacts {
    $contact = $this->entityManager->getRepository(Contacts::class)->find($id);
    if (!$contact) {
      throw new \RuntimeException('Contact not found');
    }

    $contact->setName($name);
    $contact->setEmail($email);
    $contact->setPhone($phone);
    $contact->setCompany($company);
    $contact->setUpdatedAt(new \DateTimeImmutable());

    $this->entityManager->flush();

    return $contact;
  }

  public function deleteContact(
    int $id
  ): void {
    $contact = $this->entityManager->getRepository(Contacts::class)->find($id);
    if (!$contact) {
      throw new \RuntimeException('Contact not found');
    }

    $this->entityManager->remove($contact);
    $this->entityManager->flush();
  }
}
