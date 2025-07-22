<?php
declare(strict_types=1);
namespace App\Domain\Repository;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product) ;
    public function findById(string $id): ?Product;
    public function findByIds(array $ids): array;
    public function reduceStock(string $id) : void;
}
