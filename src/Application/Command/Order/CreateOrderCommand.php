<?php
declare(strict_types=1);
namespace App\Application\Command\Order;

use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Status;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateOrderCommand 
{
  #[Assert\NotBlank()]
  public readonly string $customerEmail;
  #[Assert\NotBlank()]
  public readonly Status $status;
  public readonly ?Money $totalAmount;
   /**
   * @var OrderItemInputDTO[]
   */
  public readonly array $items;
 
  public function __construct(
                              string $customerEmail,
                              array $items
                              ) 
  {
    $this->customerEmail = $customerEmail;
    $this->status = new Status('pending') ; 
    $this->totalAmount = new Money('0.00');
    $this->items = $items;
  }

}