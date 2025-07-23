<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Doctrine\Order;

use App\Domain\Entity\Product;
use App\Domain\Exception\ConcurrencyConflictException;
use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;

final class DoctrineProductRepository implements ProductRepositoryInterface
{
  public function __construct(private EntityManagerInterface $em)
  {}
  public function save(Product $product)
  {
    $this->em->persist($product);
    try {
      $this->em->flush();  
    } catch (OptimisticLockException $e) {
      $this->em->clear(); 
      throw new ConcurrencyConflictException('Change not take place do to ConcurrencyConflict try again');
    }  
    
  }
  public function findById(string $id): ?Product
  {
    return $this->em->getRepository(Product::class)->findOneBy(['id'=>$id]);
  }
}