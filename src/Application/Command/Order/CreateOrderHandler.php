<?php 
declare(strict_types=1);
namespace App\Application\Command\Order;

use App\Domain\Entity\Order;
use App\Domain\Entity\OrderItem;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class CreateOrderHandler
{
  public function __construct(private EntityManagerInterface $em,private MessageBusInterface $bus ,private OrderRepositoryInterface $orderRepo,private ProductRepositoryInterface $productRepo) {
    
  }  
  public function __invoke(CreateOrderCommand $command) : void
  {
    $maxRetries = 3;
    $attempt = 0;
    do {
      try {
        $order = new Order($command->customerEmail);
        
        foreach($command->items as $item){
          $product = $this->productRepo->findById($item->productId);
          
          if (!$product) {
              throw new \InvalidArgumentException("Product not found.");
          }
          $product->decreaseStockQuantity($item->quantity);
          $order->addItem(new OrderItem($product,$order,$product->getSku(),$item->quantity,$product->getPrice()));
        }
        $order->setTotalAmount();
        $this->orderRepo->save($order);
        foreach($order->pullOrderEvents() as $event){
          $this->bus->dispatch($event);
        }  
        return;
      } catch (OptimisticLockException $e) {
          $this->em->clear(); 
          if ($attempt >= $maxRetries) {
              throw new \RuntimeException("Could not update stock after {$maxRetries} attempts.");
          }
          $attempt++;
          usleep(100000);
      }
    } while ($attempt < $maxRetries);
    
  }  
}
