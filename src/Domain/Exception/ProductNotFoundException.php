<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException;

final class ProductNotFoundException extends DomainException
{
  public function __construct(?string $message=null)
  {
      parent::__construct($message??'Product not Found.');
  }
}
