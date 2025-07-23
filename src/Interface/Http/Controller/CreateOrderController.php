<?php
declare(strict_types=1);
namespace App\Interface\Http\Controller;

use App\Application\Command\Order\CreateOrderCommand;
use App\Application\Command\Order\OrdeInputDTO;
use App\Application\Command\Order\OrderItemInputDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateOrderController extends AbstractController
{
  public function __construct(private MessageBusInterface $bus,private readonly ValidatorInterface $validator,)
  {
    
  }
  #[Route('/order', name: 'create_order', methods: ['POST'])]
  public function __invoke(Request $request) : JsonResponse
  {
    $data = json_decode($request->getContent(), true);
    if (!is_array($data)) {
      return $this->json(['error' => 'Invalid JSON payload'], 400);
    }
    $items = $data['items'] ?? [];
    $email = $data['email'];
    try {
          $orderItems = [];
          foreach ($items as $itemData) {
              $orderItems[] = new OrderItemInputDTO($itemData['productId'] ?? '',$itemData['quantity'] ?? 0);
          }
          $orderInuptDTO = new OrdeInputDTO($email,$orderItems);            
          $violations = $this->validator->validate($orderInuptDTO);
          
          if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], 400);
          }
          $command = new CreateOrderCommand($orderInuptDTO->email,$orderInuptDTO->items);
          $this->bus->dispatch($command);
          return $this->json(['message' => 'Order submitted']);
        } catch (HandlerFailedException | \InvalidArgumentException | \DomainException $e) {
            if($e instanceof HandlerFailedException)
              $e = $e->getPrevious();
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
  }
}