<?php 
declare(strict_types=1);
namespace App\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Money 
{
  #[ORM\Column(name: "price_amount", type: "decimal", precision: 10, scale: 2)]
  private string $amount;
  #[ORM\Column(name: "price_currency", type: "string", length: 3)]
  private string $currency;

  public function __construct(string $amount, string $currency = 'EUR')
  {
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $amount)) {
            throw new \InvalidArgumentException("Invalid amount format: $amount");
        }

        if (!preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new \InvalidArgumentException("Invalid currency format: $currency");
        }
    #ToDo normalizeAmount
    $this->amount = $amount;
    $this->currency = $currency;
  }
  public function getAmount(): string
  {
    return $this->amount;
  }
  public function getCurrency(): string
  {
    return $this->currency;
  }
  public function add(Money $money):self 
  {
    $this->assertSameCurrency($money);
    $result = bcadd($this->amount , $money->amount , 2);
    return new self($result,$this->currency);
  }
  public function multiply(int|float|string $multiplier): Money
  {
      $multiplier = (string) $multiplier;

      if (!preg_match('/^\d+(\.\d+)?$/', $multiplier)) {
          throw new \InvalidArgumentException("Invalid multiplier: $multiplier");
      }

      $result = bcmul($this->amount, $multiplier, 2);

      return new self($result, $this->currency);
  }
  private function assertSameCurrency(Money $other): void
  {
      if ($this->currency !== $other->currency) {
          throw new \InvalidArgumentException("Currencies must match: {$this->currency} !== {$other->currency}");
      }
  }
  public function __toString(): string
  {
      return "{$this->amount} {$this->currency}";
  }

}
