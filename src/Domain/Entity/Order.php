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
#[ORM\Table(name: 'orders')]
class Order
{
  #[ORM\Id]
  #[ORM\Column(type: 'uuid')]
  private string $id;

  #[ORM\Column(length: 255)]
  private string $customerEmail;

  #[ORM\Column(type:'string',length: 255)]
  private ?string $status;

  #[ORM\Embedded(class: Money::class)]
  private ?Money $totalAmount;

  #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order',cascade: ['persist'], orphanRemoval: true)]
  private ?Collection $items;

  public function __construct( string $customerEmail, ?array $items=null , ?Status $status=null, ?Money $totalAmount = null)
  {
      $this->id = Uuid::v4()->toRfc4122();
      $this->customerEmail = $customerEmail;
      $this->status = $status?? new Status('pending')->value();
      $this->totalAmount = $totalAmount ?? new Money('0.00');
      $this->items =  $items ? new ArrayCollection($items) : new ArrayCollection();
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
  public function getStatus(): string
  {
      return $this->status;
  }
  public function getTotalAmount(): Money
  {
    return $this->totalAmount;
  }
  public function setTotalAmount() : void
  {
    $this->totalAmount = array_reduce($this->items->toArray(),function($total , OrderItem $item){
        return $total->add($item->getPrice()->multiply($item->getQuantity())) ;
    },new Money('0.00',$this->totalAmount->getCurrency()));
  }
  
}