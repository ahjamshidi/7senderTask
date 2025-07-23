<?php 
declare(strict_types=1);
namespace App\Application\Command\Order;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class OrdeInputDTO
{

  #[Assert\NotBlank]
  #[Assert\Email]
  public string $email;
  /**
   * @var OrderItemInputDTO[]
   */
  #[Assert\NotBlank]
  #[Assert\Type('array')]
  #[Assert\Valid()]
  public array $items;

  public function __construct(string $email , array $items)
  {
    $this->items = $items;
    $this->email = $email;
  }  
}
