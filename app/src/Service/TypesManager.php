<?php

namespace App\Service;

use App\Entity\Types;
use Doctrine\ORM\EntityManagerInterface;

class TypesManager
{
  public function __construct(
    private EntityManagerInterface $entityManager
  ) {}

  public function createType(string $name): Types
  {
    $type = new Types();
    $type->setName($name);

    $this->entityManager->persist($type);
    $this->entityManager->flush();

    return $type;
  }

  public function updateType(int $id, string $name): Types
  {
    $type = $this->entityManager->getRepository(Types::class)->find($id);
    if (!$type) {
      throw new \RuntimeException('Type not found');
    }

    $type->setName($name);
    $type->setUpdatedAt(new \DateTimeImmutable());

    $this->entityManager->flush();

    return $type;
  }

  public function deleteType(int $id): void
  {
    $type = $this->entityManager->getRepository(Types::class)->find($id);
    if (!$type) {
      throw new \RuntimeException('Type not found');
    }

    $this->entityManager->remove($type);
    $this->entityManager->flush();
  }
}
