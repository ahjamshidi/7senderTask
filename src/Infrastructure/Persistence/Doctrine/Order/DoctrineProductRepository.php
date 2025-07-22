<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Doctrine\Order;

use App\Domain\Entity\Order;
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
  /**
   * @param string[] $id
   */
  public function findByIds(array $id): array
  {
    return $this->em->getRepository(Product::class)->findBy(['id'=>$id]);
  }
  public function reduceStock(string $id): void
  {
    // $this->em->getRepository(Product::class)->createQueryBuilder()->update();
  }
}