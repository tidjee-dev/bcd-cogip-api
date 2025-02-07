<?php

namespace App\Service;

use App\Entity\Companies;
use App\Entity\Types;
use Doctrine\ORM\EntityManagerInterface;

class CompaniesManager
{
  public function __construct(
    private EntityManagerInterface $entityManager
  ) {}
  
  public function createCompany(
    string $name,
    Types $type,
    string $country,
    string $tva
  ): Companies {
    $company = new Companies();
    $company->setName($name);
    $company->setType($type);
    $company->setCountry($country);
    $company->setTva($tva);

    $this->entityManager->persist($company);
    $this->entityManager->flush();

    return $company;
  }

  public function updateCompany(
    int $id,
    string $name,
    Types $type,
    string $country,
    string $tva
  ): Companies {
    $company = $this->entityManager->getRepository(Companies::class)->find($id);
    if (!$company) {
      throw new \RuntimeException('Company not found');
    }

    $company->setName($name);
    $company->setType($type);
    $company->setCountry($country);
    $company->setTva($tva);
    $company->setUpdatedAt(new \DateTimeImmutable());

    $this->entityManager->flush();

    return $company;
  }

  public function deleteCompany(
    int $id
  ): void {
    $company = $this->entityManager->getRepository(Companies::class)->find($id);
    if (!$company) {
      throw new \RuntimeException('Company not found');
    }

    $this->entityManager->remove($company);
    $this->entityManager->flush();
  }
}
