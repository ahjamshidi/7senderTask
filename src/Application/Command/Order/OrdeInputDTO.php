<?php 
declare(strict_types=1);
namespace App\Application\Command\Order;

use Symfony\Component\Validator\Constraints as Assert;

final class OrdeInputDTO
{

  #[Assert\NotBlank]
  #[Assert\Email]
  public readonly string $email;
  /**
   * @var OrderItemInputDTO[]
   */
  #[Assert\NotBlank]
  #[Assert\Type('array')]
  #[Assert\Valid()]
  public readonly array $items;

  public function __construct(string $email , array $items)
  {
    $this->items = $items;
    $this->email = $email;
  }  
}
