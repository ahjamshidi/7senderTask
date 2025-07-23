<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function save(Order $order) ;
    public function getById(string $id) : Order ;
}
