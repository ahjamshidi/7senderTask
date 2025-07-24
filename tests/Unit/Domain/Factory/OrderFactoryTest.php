<?php
declare(strict_types=1);

use App\Domain\Entity\Product;
use App\Domain\Factory\OrderFactory;
use App\Domain\Factory\OrderFactoryInput;
use App\Domain\Factory\OrderItemFactoryInput;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;
class OrderFactoryTest extends TestCase
{
  public function test_valid_order_return():void
  {
    $prodRepo = $this->createMock(ProductRepositoryInterface::class);
    $orderRepo = $this->createMock(OrderRepositoryInterface::class);
    $product = $this->createMock(Product::class);
    $product->expects($this->once())
            ->method('decreaseStockQuantity');

        $product->method('getSku')->willReturn('SKU-1');
        $product->method('getPrice')->willReturn(new Money('50.00'));

    $prodRepo->expects($this->exactly(1))->method('findById')->willReturn($product);
    $items = [
        ["productId"=>"250f30a8-9b50-4b07-a156-bba78a9a6514","quantity"=>2],
      ];
    $orderFactoryItems = [];
    foreach($items as $item){
      $orderFactoryItems[] = new OrderItemFactoryInput($item['productId'],$item['quantity']);
    }
    $orderFactoryInput = new OrderFactoryInput('aadfs',$orderFactoryItems);
    $factory = new OrderFactory($prodRepo);
    $order = $factory->__invoke($orderFactoryInput);

    $this->assertEquals('100.00',$order->getTotalAmount()->getAmount());
  }
}