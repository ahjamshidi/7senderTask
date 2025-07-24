<?php 
declare(strict_types=1);
namespace App\Domain\Factory;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class OrderItemFactoryInput
{
  public function __construct(public string $productId,public int $quantity)
  {
 
  }
  
}
