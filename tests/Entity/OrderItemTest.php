<?php
namespace inklabs\kommerce\Entity;

use inklabs\kommerce\Service\Pricing;

class OrderItemTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $product = new Product;
        $product->setSku('sku');
        $product->setname('test name');
        $product->setUnitPrice(500);
        $product->setQuantity(10);

        $productQuantityDiscount = new ProductQuantityDiscount;
        $productQuantityDiscount->setType('exact');
        $productQuantityDiscount->setQuantity(2);
        $productQuantityDiscount->setValue(100);

        $product->addProductQuantityDiscount($productQuantityDiscount);

        $catalogPromotion = new CatalogPromotion;
        $catalogPromotion->setName('20% OFF');
        $catalogPromotion->setType('percent');
        $catalogPromotion->setValue(20);

        $pricing = new Pricing;
        $pricing->setCatalogPromotions([$catalogPromotion]);
        $pricing->setProductQuantityDiscounts([$productQuantityDiscount]);

        $cartItem = new CartItem($product, 2);
        $orderItem = new OrderItem($cartItem, $pricing);

        $order = new Order(new Cart, $pricing);
        $orderItem->setOrder($order);

        $this->assertSame(2, $orderItem->getQuantity());
        $this->assertSame('sku', $orderItem->getProductSku());
        $this->assertSame('test name', $orderItem->getProductName());
        $this->assertSame('20% OFF, Buy 2 or more for $1.00 each', $orderItem->getDiscountNames());
        $this->assertSame(null, $orderItem->getId());
        $this->assertTrue($orderItem->getPrice() instanceof Price);
        $this->assertTrue($orderItem->getProduct() instanceof Product);
        $this->assertTrue($orderItem->getCatalogPromotions()[0] instanceof CatalogPromotion);
        $this->assertTrue($orderItem->getProductQuantityDiscount() instanceof ProductQuantityDiscount);
        $this->assertTrue($orderItem->getView() instanceof View\Orderitem);
    }

    public function testCreateOrderItemWithDiscounts()
    {
        $catalogPromotion = new CatalogPromotion;
        $catalogPromotion->setName('10% OFF');
        $catalogPromotion->setType('percent');
        $catalogPromotion->setValue(10);

        $productQuantityDiscount = new ProductQuantityDiscount;
        $productQuantityDiscount->setType('exact');
        $productQuantityDiscount->setQuantity(2);
        $productQuantityDiscount->setValue(400);

        $product = new Product;
        $product->setUnitPrice(500);
        $product->setQuantity(10);
        $product->addProductQuantityDiscount($productQuantityDiscount);

        $pricing = new Pricing;
        $pricing->setCatalogPromotions([$catalogPromotion]);
        $pricing->setProductQuantityDiscounts([$productQuantityDiscount]);

        $cartItem = new CartItem($product, 2);
        $orderItem = new OrderItem($cartItem, $pricing);

        $order = new Order(new Cart, $pricing);
        $orderItem->setOrder($order);

        $this->assertTrue($orderItem->getPrice() instanceof Price);
        $this->assertSame('10% OFF, Buy 2 or more for $4.00 each', $orderItem->getDiscountNames());
        $this->assertSame(null, $orderItem->getId());
    }
}
