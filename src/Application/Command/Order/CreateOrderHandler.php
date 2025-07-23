<?php 
declare(strict_types=1);
namespace App\Application\Command\Order;

use App\Domain\Entity\Order;
use App\Domain\Entity\OrderItem;
use App\Domain\Exception\ConcurrencyConflictException;
use App\Domain\Exception\ProductNotFoundException;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class CreateOrderHandler
{
  public function __construct(private MessageBusInterface $bus ,private OrderRepositoryInterface $orderRepo,private ProductRepositoryInterface $productRepo) {
    
  }  
  public function __invoke(CreateOrderCommand $command) : void
  {
    $order = new Order($command->customerEmail);
    foreach($command->items as $item){
      $product = $this->productRepo->findById($item->productId);
      
      if (!$product) {
          throw new ProductNotFoundException("There is not a product with {$item->productId} id.");
      }
      $product->decreaseStockQuantity($item->quantity);
      $order->addItem(new OrderItem($product,$order,$product->getSku(),$item->quantity,$product->getPrice()));
    }
    $order->setTotalAmount();
    $this->orderRepo->save($order);
    foreach($order->pullOrderEvents() as $event){
      $this->bus->dispatch($event);
    }  
  }  
}
