<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException;

final class ConcurrencyConflictException extends DomainException
{
  public function __construct(?string $message=null)
  {
      parent::__construct($message??'Conflict: changes not happend try again.');
  }
}
