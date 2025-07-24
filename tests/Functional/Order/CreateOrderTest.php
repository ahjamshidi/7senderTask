<?php 
declare(strict_types=1);

use App\Domain\Entity\Product;
use App\Domain\ValueObject\Money;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateOrderTest extends WebTestCase
{
  public function test_create_order():void
  {
    $client = static::createClient();
    $container = static::getContainer();
    $productSample = ["name"=> "Wireless Mouse","sku"=> "WM-002","price"=> 19.99,"stockQuantity"=> 120];
    $name = $productSample['name'];
    $sku = $productSample['sku'];
    $price = new Money((string)$productSample['price'],'EUR');
    $stockQuantity=$productSample['stockQuantity'];
    $product = new Product($name,$sku,$price,$stockQuantity);
    $em = $container->get('doctrine')->getManager();
    $em->persist($product);
    $em->flush();

    $client->request(method:'POST',uri:'/order',parameters:[
      'CONTENT_TYPE' => 'application/json'
    ],content:json_encode([
      'email' => 'john@example.com',
      'items' => [
          ['productId' => $product->getId(), 'quantity' => 2]
      ]
    ]));
    $response = $client->getResponse();
    $this->assertResponseIsSuccessful();
    $this->assertJson($response->getContent());
    $this->assertStringContainsString('Order submitted', $response->getContent());

  }
}