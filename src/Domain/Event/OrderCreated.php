<?php 
declare(strict_types=1);
namespace App\Domain\Event;

final readonly class OrderCreated
{
  public string $orderId;
  public function __construct(string $orderId)
  {
    $this->orderId = $orderId;
  }
}
