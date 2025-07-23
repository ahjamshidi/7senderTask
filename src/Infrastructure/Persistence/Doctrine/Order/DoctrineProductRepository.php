<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Doctrine\Order;

use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineProductRepository implements ProductRepositoryInterface
{
  public function __construct(private EntityManagerInterface $em)
  {}
  public function save(Product $product)
  {
      $this->em->persist($product);
      $this->em->flush();
  }
  public function findById(string $id): ?Product
  {
    return $this->em->getRepository(Product::class)->findOneBy(['id'=>$id]);
  }
}