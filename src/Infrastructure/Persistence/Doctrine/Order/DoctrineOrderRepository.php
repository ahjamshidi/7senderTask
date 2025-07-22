<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Doctrine\Order;

use App\Domain\Entity\Order;
use App\Domain\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineOrderRepository implements OrderRepositoryInterface
{
  public function __construct(private EntityManagerInterface $em)
  {}
  public function save(Order $order)
  {
      $this->em->persist($order);
      $this->em->flush();
  }
  public function getById(int $id): Order
  {
    return $this->em->getRepository(Order::class)->findOneBy(['id'=>$id]);
  }
}