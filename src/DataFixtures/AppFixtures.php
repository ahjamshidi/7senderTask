<?php

namespace App\DataFixtures;

use App\Domain\Entity\Product;
use App\Domain\ValueObject\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dataPath = __DIR__ . '/sample_products.json';
        $jsonData = file_get_contents($dataPath);
        $products = json_decode($jsonData, true);
        foreach ($products as $key => $product) {
            $name = $product['name'];
            $sku = $product['sku'];
            $price = new Money((string)$product['price'],'EUR');
            $stockQuantity=$product['stockQuantity'];
            $product = new Product($name,$sku,$price,$stockQuantity);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
