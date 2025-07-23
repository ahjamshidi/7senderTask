<?php 
declare(strict_types=1);
namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity]
class Invoice
{
  #[ORM\Id]
  #[ORM\Column(type: 'uuid')]
  private string $id;
  #[ORM\Embedded(class: Money::class)]
  private Money $amount;
  #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'invoices')]
  #[ORM\JoinColumn(nullable: false)]
  private Order $order;
  #[ORM\Column(type: 'datetime',nullable:false)]
  private DateTime $generatedAt;
  public function __construct(Order $order,Money $amount)
  {
    $this->id = Uuid::v4()->toRfc4122();
    $this->amount = $amount;
    $this->generatedAt = new \DateTime();
    $this->order = $order;
  }
  public function getId(): string
  {
      return $this->id;
  }
  public function getAmount(): Money
  {
      return $this->amount;
  }
  public function getGenerateAt():DateTime
  {
    return $this->generatedAt;
  }
  public function getOrder(): Order
  {
      return $this->order;
  }
}