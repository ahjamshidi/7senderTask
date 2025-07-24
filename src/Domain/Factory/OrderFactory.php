<?php 
declare(strict_types=1);
namespace App\Domain\Factory;

use App\Domain\Entity\Order;
use App\Domain\Entity\OrderItem;
use App\Domain\Exception\ProductNotFoundException;
use App\Domain\Factory\OrderFactoryInput;
use App\Domain\Repository\ProductRepositoryInterface;

class OrderFactory
{
  public function __construct(private ProductRepositoryInterface $productRepo)
  {
    
  }
  public function __invoke(OrderFactoryInput $orderFactoryInput)
  {
    $order = new Order($orderFactoryInput->email);
    foreach($orderFactoryInput->items as $item){
      $product = $this->productRepo->findById($item->productId);
      
      if (!$product) {
          throw new ProductNotFoundException("There is not a product with {$item->productId} id.");
      }
      $product->decreaseStockQuantity($item->quantity);
      $order->addItem(new OrderItem($product,$order,$product->getSku(),$item->quantity,$product->getPrice()));
    }
    $order->setTotalAmount();
    return $order;
  }
}
