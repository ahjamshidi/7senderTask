<?php 
declare(strict_types=1);
namespace App\Domain\Factory;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class OrderFactoryInput
{
  /**
   * @param OrderItemFactoryInput[] $items
   */
  public function __construct(public string $email , public array $items)
  {
    
  }  
}
