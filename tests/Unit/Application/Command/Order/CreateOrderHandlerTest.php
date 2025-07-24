<?php
declare(strict_types=1);

use App\Application\Command\Order\CreateOrderCommand;
use App\Application\Command\Order\CreateOrderHandler;
use App\Application\Command\Order\OrdeInputDTO;
use App\Application\Command\Order\OrderItemInputDTO;
use App\Domain\Entity\Order;
use App\Domain\Event\OrderCreated;
use App\Domain\Factory\OrderFactory;
use App\Domain\Repository\OrderRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateOrderHandlerTest extends TestCase
{
  public function test_order_is_saved_and_event_dispatched():void
  {
    $orderRepo = $this->createMock(OrderRepositoryInterface::class);
    $orderFactory = $this->createMock(OrderFactory::class);
    $eventBus = $this->createMock(MessageBusInterface::class);
    $order = $this->createMock(Order::class);
    $order->expects($this->once())->method('pullOrderEvents')->willReturn([new OrderCreated('2')]);

    $items = [
        ["productId"=>"250f30a8-9b50-4b07-a156-bba78a9a6514","quantity"=>2],
        ["productId"=>"071c3465-4a2d-47ed-a496-05f1dd5f8877","quantity"=>3],
        ["productId"=>"d2af10b5-c929-4c66-a7d5-eb20a26f99b3","quantity"=>4]
      ];
    $orderItems = [];
    foreach($items as $item){
      $orderItems[] = new OrderItemInputDTO($item['productId'],$item['quantity']);
    }
    $orderInuptDTO = new OrdeInputDTO('amir@gmail.com',$orderItems);
    $command = new CreateOrderCommand($orderInuptDTO->email,$orderInuptDTO->items);

    $orderFactory->expects($this->once())->method('__invoke')->willReturn($order);
    
    $orderRepo->expects($this->once())->method('save')->with($order);

    $eventBus->expects($this->once())
         ->method('dispatch')->willReturn(new Envelope(new OrderCreated('2')));
         
    $handler = new CreateOrderHandler($eventBus,$orderRepo,$orderFactory);
    $handler->__invoke($command);
  }
}
