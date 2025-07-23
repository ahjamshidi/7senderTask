<?php 
declare(strict_types=1);
namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity]
class Product 
{
  #[ORM\Id]
  #[ORM\Column(type: 'uuid')]
  private string $id;

  #[ORM\Column(length: 255,nullable: false)]
  private string $name;

  #[ORM\Column(length: 255,unique:true,nullable: false)]
  private string $sku;

  #[ORM\Embedded(class: Money::class)]
  private Money $price;

  #[ORM\Column(nullable: false)]
  private int $stockQuantity;

  #[ORM\Version]
  #[ORM\Column(type:"integer")]
  private int $version = 1;
  public function __construct(string $name,string $sku,Money $price,int $stockQuantity)
  {
      $this->id = Uuid::v4()->toRfc4122();
      $this->name = $name;
      $this->sku = $sku;
      $this->price = $price;
      $this->stockQuantity = $stockQuantity;
  }

  public function getId(): string
  {
      return $this->id;
  }
  public function getName(): string
  {
      return $this->name;
  }
  public function getSku(): string
  {
      return $this->sku;
  }
  public function getPrice(): Money
  {
    return $this->price;
  }
  public function getStockQuantity(): int
  {
    return $this->stockQuantity;
  }
  public function decreaseStockQuantity(int $quantity) : void
  {
    if ($quantity <= 0) {
        throw new \InvalidArgumentException('Quantity must be positive.');
    }

    if ($quantity > $this->stockQuantity) {
        throw new \DomainException('Not enough stock available.');
    }

    $this->stockQuantity -= $quantity;
  }
  
}