<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException;

final class ProductOutOfStockException extends DomainException
{
  public function __construct(?string $message=null)
  {
      parent::__construct($message??'Product is out of stock.');
  }
}
