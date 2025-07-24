<?php 
declare(strict_types=1);
namespace App\Application\Command\Order;

use App\Domain\Entity\Invoice;
use App\Domain\Event\OrderCreated;
use App\Domain\Repository\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
final class ProcessPendingOrderHandler
{
  public function __construct(private OrderRepositoryInterface $orderRepo,private LoggerInterface $logger)
  {
   
  }
  public function __invoke(OrderCreated $orderCreatedEvent)
  {
    try{
      $order = $this->orderRepo->getById($orderCreatedEvent->orderId);
      if (!$order) {
          throw new \InvalidArgumentException("Order not found.");
      }
      $order->changeStatusToProcessed();
      $this->orderRepo->save($order);
      $invoice = new Invoice($order,$order->getTotalAmount());
      $order->addInvoice($invoice);
      $order->changeStatusToInvoiced();
      
      $this->orderRepo->save($order);
    }catch(\Throwable $e){
      $this->logger->error('Order Process Error',['exception'=>$e,'orderId'=>$order->getId()]);
    }
    

  }
}
