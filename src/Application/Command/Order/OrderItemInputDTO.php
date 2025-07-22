<?php 
declare(strict_types=1);
namespace App\Application\Command\Order;

use Symfony\Component\Validator\Constraints as Assert;

final class OrderItemInputDTO
{
  #[Assert\NotBlank]
  #[Assert\Uuid(message: "Each product ID must be a valid UUID.")]
  public readonly string $productId;

  #[Assert\NotBlank]
  #[Assert\Positive(message: 'Quantity must be a positive number.')]
  public readonly int $quantity;
  public function __construct(string $productId,int $quantity)
  {
    $this->productId = $productId;
    $this->quantity = $quantity;
  }
  
}
