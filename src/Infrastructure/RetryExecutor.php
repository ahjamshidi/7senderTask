<?php 
declare(strict_types=1);
namespace App\Infrastructure;

final class RetryExecutor
{
  public function run(callable $operation, int $maxAttempts, array $retryOn): mixed
    {
        $attempts = 0;
        while (true) {
            try {
                return $operation();
            } catch (\Throwable $e) {
                if (!array_filter($retryOn, fn ($class) => ($e instanceof $class or 
                                                            $e->getPrevious() instanceof $class))) {
                    throw $e;
                }

                $attempts++;
                if ($attempts >= $maxAttempts) {
                    throw $e;
                }
                // backoff for 100ms
                usleep(100000);
            }
        }
    }
}
