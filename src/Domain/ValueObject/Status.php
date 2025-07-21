<?php 
declare(strict_types=1);
namespace App\Domain\ValueObject;

final class Status 
{
  private const ALLOWED = ['pending', 'processed', 'invoiced'];
  private string $value;

  public function __construct(string $value)
  {
    if (!in_array($value, self::ALLOWED)) {
      throw new \InvalidArgumentException("Invalid Order Status: $value");
    }
    $this->value = $value;
  }
  public function value(): string
  {
      return $this->value;
  }
   public function __toString(): string
  {
    return $this->value;
  }

}
