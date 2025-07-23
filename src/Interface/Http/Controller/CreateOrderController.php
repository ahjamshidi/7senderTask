<?php
declare(strict_types=1);
namespace App\Interface\Http\Controller;

use App\Application\Command\Order\CreateOrderCommand;
use App\Application\Command\Order\OrdeInputDTO;
use App\Application\Command\Order\OrderItemInputDTO;
use App\Domain\Exception\ConcurrencyConflictException;
use App\Domain\Exception\ProductNotFoundException;
use App\Domain\Exception\ProductOutOfStockException;
use App\Infrastructure\RetryExecutor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateOrderController extends AbstractController
{
  public function __construct(private RetryExecutor $retryExecutor, private MessageBusInterface $bus,private readonly ValidatorInterface $validator,)
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
          try {
             $this->retryExecutor->run(
                                        fn()=>$this->bus->dispatch($command),
                                        3,
                                        [ConcurrencyConflictException::class]
                                      );
            
          } catch (HandlerFailedException $e) {
            $previous = $e->getPrevious();
            throw $previous;
          }
          return $this->json(['message' => 'Order submitted']);
        } catch ( ConcurrencyConflictException | ProductNotFoundException | ProductOutOfStockException | \InvalidArgumentException | \DomainException $e) {
            return $this->json(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
  }
}