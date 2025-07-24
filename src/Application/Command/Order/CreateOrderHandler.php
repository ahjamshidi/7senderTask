<?php 
declare(strict_types=1);
namespace App\Application\Command\Order;

use App\Domain\Factory\OrderFactory;
use App\Domain\Factory\OrderFactoryInput;
use App\Domain\Factory\OrderItemFactoryInput;
use App\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class CreateOrderHandler
{
  public function __construct(
    private MessageBusInterface $bus ,
    private OrderRepositoryInterface $orderRepo, 
    private OrderFactory $orderFactory
  ) 
  {

  } 

  public function __invoke(CreateOrderCommand $command) : void
  {
    $order = $this->orderFactory->__invoke(new OrderFactoryInput($command->customerEmail,array_map(function($item){
      return new OrderItemFactoryInput($item->productId,$item->quantity);
    },$command->items)));
    $this->orderRepo->save($order);
    foreach($order->pullOrderEvents() as $event){
      $this->bus->dispatch($event);
    } 
  }  
}
