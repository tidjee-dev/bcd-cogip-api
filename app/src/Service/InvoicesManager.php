<?php

namespace App\Service;

use App\Entity\Companies;
use App\Entity\Invoices;
use Doctrine\ORM\EntityManagerInterface;

class InvoicesManager
{
  public function __construct(
    private EntityManagerInterface $entityManager
  ) {}

  public function createInvoice(
    string $ref,
    Companies $company
  ): Invoices {
    $invoice = new Invoices();
    $invoice->setRef($ref);
    $invoice->setCompany($company);


    $this->entityManager->persist($invoice);
    $this->entityManager->flush();
    return $invoice;
  }

  public function updateInvoice(
    int $id,
    string $ref,
    Companies $company
  ): Invoices {
    $invoice = $this->entityManager->getRepository(Invoices::class)->find($id);
    if (!$invoice) {
      throw new \RuntimeException('Invoice not found');
    }

    $invoice->setRef($ref);
    $invoice->setCompany($company);
    $invoice->setUpdatedAt(new \DateTime());

    $this->entityManager->flush();

    return $invoice;
  }

  public function deleteInvoice(
    int $id
  ): void {
    $invoice = $this->entityManager->getRepository(Invoices::class)->find($id);
    if (!$invoice) {
      throw new \RuntimeException('Invoice not found');
    }

    $this->entityManager->remove($invoice);
    $this->entityManager->flush();
  }
}
