<?php
declare(strict_types=1);

use App\Application\Command\Order\ProcessPendingOrderHandler;
use App\Domain\Entity\Order;
use App\Domain\Entity\Product;
use App\Domain\Event\OrderCreated;
use App\Domain\Factory\OrderFactory;
use App\Domain\Factory\OrderFactoryInput;
use App\Domain\Factory\OrderItemFactoryInput;
use App\Domain\Repository\OrderRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\Money;
use App\Tests\Shared\DatabaseTestCase;
use Psr\Log\LoggerInterface;

class ProcessPendingOrderHandlerIntegrationTest extends DatabaseTestCase
{
  public function test_process_order_and_make_invoice()
  {
    $orderRepo =$this->getContainer()->get(OrderRepositoryInterface::class);
    $prodRepo =$this->getContainer()->get(ProductRepositoryInterface::class);
    
    $productSample = ["name"=> "Wireless Mouse","sku"=> "WM-001","price"=> 19.99,"stockQuantity"=> 120];
    $name = $productSample['name'];
    $sku = $productSample['sku'];
    $price = new Money((string)$productSample['price'],'EUR');
    $stockQuantity=$productSample['stockQuantity'];
    $product = new Product($name,$sku,$price,$stockQuantity);
    $prodRepo->save($product);

    $orderFactory = new OrderFactory($prodRepo);
    $order = $orderFactory->__invoke(new OrderFactoryInput('amir@example.com',[new OrderItemFactoryInput($product->getId(),2)]));
    $orderRepo->save($order);
    $logger = $this->getContainer()->get(LoggerInterface::class);
    $processPendingOrderHandler = new ProcessPendingOrderHandler($orderRepo,$logger);

    $orderEvent = new OrderCreated($order->getId());

    $processPendingOrderHandler->__invoke($orderEvent);

    $orders = $this->em->getRepository(Order::class)->findAll();
    $this->assertCount(1, $orders);
    $order = $orders[0];
    $invoices = $order->getInvoices();
    $this->assertCount(1, $invoices);
    $invoice = $invoices[0];
    $this->assertEquals('invoiced', $order->getStatus());
    $this->assertEquals('39.98', $invoice->getAmount()->getAmount());

  }
}
