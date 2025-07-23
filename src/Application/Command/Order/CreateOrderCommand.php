<?php
declare(strict_types=1);
namespace App\Application\Command\Order;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateOrderCommand 
{
  #[Assert\NotBlank()]
  public string $customerEmail;
   /**
   * @var OrderItemInputDTO[]
   */
  public array $items;
 
  public function __construct(
                              string $customerEmail,
                              array $items
                              ) 
  {
    $this->customerEmail = $customerEmail;
    $this->items = $items;
  }

}