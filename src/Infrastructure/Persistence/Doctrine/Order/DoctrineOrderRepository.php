<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Doctrine\Order;

use App\Domain\Entity\Order;
use App\Domain\Exception\ConcurrencyConflictException;
use App\Domain\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;

final class DoctrineOrderRepository implements OrderRepositoryInterface
{
  public function __construct(private EntityManagerInterface $em)
  {}
  public function save(Order $order)
  {
      $this->em->persist($order);
      try {
        $this->em->flush();  
      } catch (OptimisticLockException $e) {
        $this->em->clear(); 
        throw new ConcurrencyConflictException('Change not take place do to ConcurrencyConflict try agan');
      }  
  }
  public function getById(string $id): Order
  {
    return $this->em->getRepository(Order::class)->findOneBy(['id'=>$id]);
  }
}