<?php
namespace inklabs\kommerce\Entity;

use inklabs\kommerce\Lib;
use inklabs\kommerce\View;
use Symfony\Component\Validator\Validation;
use inklabs\kommerce\tests\Helper;

class CartItemTest extends Helper\DoctrineTestCase
{
    public function testCreate()
    {
        $cartItem = $this->getDummyFullCartItem();

        $pricing = new Lib\Pricing;

        $validator = Validation::createValidatorBuilder()
            ->addMethodMapping('loadValidatorMetadata')
            ->getValidator();

        $this->assertEmpty($validator->validate($cartItem));
        $this->assertTrue($cartItem instanceof CartItem);
        $this->assertSame(2, $cartItem->getQuantity());
        $this->assertSame('P1-OP1-OV1', $cartItem->getFullSku());
        $this->assertSame(600, $cartItem->getPrice($pricing)->quantityPrice);
        $this->assertSame(60, $cartItem->getShippingWeight());
        $this->assertTrue($cartItem->getCartItemOptionProducts()[0] instanceof CartItemOptionProduct);
        $this->assertTrue($cartItem->getCartItemOptionValues()[0] instanceof CartItemOptionValue);
        $this->assertTrue($cartItem->getCartItemTextOptionValues()[0] instanceof CartItemTextOptionValue);
        $this->assertTrue($cartItem->getPrice($pricing) instanceof Price);
        $this->assertTrue($cartItem->getCart() instanceof Cart);
        $this->assertTrue($cartItem->getView() instanceof View\CartItem);
    }

    public function testClone()
    {
        $cartItem = $this->getDummyFullCartItem();
        $newCartItem = clone $cartItem;

        $this->assertNotSame($cartItem, $newCartItem);
        $this->assertNotSame($cartItem->getCartItemOptionProducts()[0], $newCartItem->getCartItemOptionProducts()[0]);
        $this->assertNotSame($cartItem->getCartItemOptionValues()[0], $newCartItem->getCartItemOptionValues()[0]);
        $this->assertNotSame($cartItem->getCartItemTextOptionValues()[0], $newCartItem->getCartItemTextOptionValues()[0]);
    }

    public function testGetOrderItem()
    {
        $cartItem = $this->getDummyFullCartItem();
        $orderItem = $cartItem->getOrderItem(new Lib\Pricing);

        $this->assertTrue($orderItem instanceof OrderItem);
        $this->assertTrue($orderItem->getProduct() instanceof Product);
        $this->assertSame(2, $orderItem->getQuantity());
        $this->assertTrue($orderItem->getPrice() instanceof Price);
        $this->assertTrue($orderItem->getOrderItemOptionProducts()[0] instanceof OrderItemOptionProduct);
        $this->assertTrue($orderItem->getOrderItemOptionValues()[0] instanceof OrderItemOptionValue);
        $this->assertTrue($orderItem->getOrderItemTextOptionValues()[0] instanceof OrderItemTextOptionValue);
    }
}
