<?php 
declare(strict_types=1);
namespace App\Domain\ValueObject;

enum Status : string
{
  case PENDING = 'pending';
  case PROCESSED = 'processed';
  case INVOICED = 'invoiced';
}
