<?php 
declare(strict_types=1);
namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity]
class Order
{
  #[ORM\Id]
  #[ORM\Column(type: 'uuid')]
  private string $id;

  #[ORM\Column(length: 255)]
  private string $customerEmail;

  #[ORM\Column(type:'string',length: 255)]
  private Status $status;

  #[ORM\Embedded(class: Money::class)]
  private Money $totalAmount;

  #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', orphanRemoval: true)]
  private Collection $items;

  public function __construct(string $customerEmail,Status $status,Money $totalAmount,array $items)
  {
      $this->id = Uuid::v4()->toRfc4122();
      $this->customerEmail = $customerEmail;
      $this->status = $status;
      $this->totalAmount = $totalAmount;
      $this->items =  new ArrayCollection($items);
  }

  public function getId(): string
  {
      return $this->id;
  }
   public function getItems(): Collection
  {
      return $this->items;
  }
  public function addItem(OrderItem $item): void
  {
      $this->items->add($item);
  }
  public function getCustomerEmail(): string
  {
      return $this->customerEmail;
  }
  public function getStatus(): Status
  {
      return $this->status;
  }
  public function getTotalAmount(): Money
  {
    return $this->totalAmount;
  }
  
}