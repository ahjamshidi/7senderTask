<?php
declare(strict_types=1);

use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;
class MoneyTest extends TestCase
{
  public function test_valid_money_should_creat_money() : void {
    $amount = '12.10';
    $currency = 'EUR';
    $money = new Money($amount,$currency);
    $this->assertSame($amount ,$money->getAmount());
    $this->assertSame($currency ,$money->getCurrency());
  }
  public function test_invalid_money_should_throw_exception() : void {
    $amount = '12.1.0s';
    $this->expectException(\InvalidArgumentException::class);
    $money = new Money($amount);
  }
  public function test_invalid_currency_should_throw_exception() : void {
    $amount = '12.10';
    $currency = 'EUREUR';
    $this->expectException(\InvalidArgumentException::class);
    $money = new Money($amount,$currency);
  }
  public function test_add_money_should_add_amount() : void {
    $amount = '12.00';
    $amount2 = '15.00';
    $sum = '27.00';
    $currency = 'EUR';
    $money = new Money($amount,$currency);
    $money2 = new Money($amount2,$currency);
    $this->assertSame($sum ,$money->add($money2)->getAmount());
  }
  public function test_add_money_with_different_currency_should_throw_exception() : void {
    $amount = '12.00';
    $amount2 = '15.00';
    $currency = 'EUR';
    $currency2 = 'USD';
    $money = new Money($amount,$currency);
    $money2 = new Money($amount2,$currency2);
    $this->expectException(\InvalidArgumentException::class);
    $money->add($money2);
  }
}