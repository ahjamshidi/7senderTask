<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Invoice;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice) ;
    public function getById(string $id) : Invoice ;
}
