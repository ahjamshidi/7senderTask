<?php 
declare(strict_types=1);

use App\Domain\Entity\Product;
use App\Domain\ValueObject\Money;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateOrderTest extends WebTestCase
{
  protected EntityManagerInterface $em;

  public function test_create_order():void
  {
    
    static::ensureKernelShutdown();
    $client = static::createClient();
    $container = static::getContainer();

    $this->em = $container->get(EntityManagerInterface::class);

    $schemaTool = new SchemaTool($this->em);
    $metadata = $this->em->getMetadataFactory()->getAllMetadata();

    $schemaTool->dropSchema($metadata);
    $schemaTool->createSchema($metadata);
    
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