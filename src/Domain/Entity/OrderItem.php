<?php 
declare(strict_types=1);
namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class OrderItem
{

  #[ORM\Id]
  #[ORM\Column(type: 'uuid')]
  private string $id;

  #[ORM\ManyToOne(targetEntity: Product::class)]
  #[ORM\JoinColumn(nullable: false)]
  private Product $product;

  #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
  #[ORM\JoinColumn(nullable: false)]
  private Order $order;

  #[ORM\Column(length: 255)]
  private string $sku;

  #[ORM\Column]
  private int $quantity;

  #[ORM\Embedded(class: Money::class)]
  private Money $price;

  public function __construct(Product $product,Order $order , string $sku,int $quantity,Money $price)
  {
      $this->id = Uuid::v4()->toRfc4122();
      $this->product = $product;
      $this->order = $order;
      $this->sku = $sku;
      $this->quantity = $quantity;
      $this->price = $price;
  }
  public function getId(): string
  {
      return $this->id;
  }
  public function getProduct(): Product
  {
      return $this->product;
  }
  public function getOrder(): Order
  {
      return $this->order;
  }
  public function getSku(): string
  {
      return $this->sku;
  }
  public function getQuantity(): int
  {
      return $this->quantity;
  }
  public function getPrice(): Money
  {
    return $this->price;
  }
  
}