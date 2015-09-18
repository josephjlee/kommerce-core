<?php
namespace inklabs\kommerce\EntityRepository;

use inklabs\kommerce\Entity;
use inklabs\kommerce\tests\Helper\EntityRepository\FakeProductRepository;
use inklabs\kommerce\tests\EntityRepository\ProductRepositoryInterfaceTest;

class ProductRepositoryDecoratorTest extends ProductRepositoryInterfaceTest
{
    public function setUp()
    {
        $this->productRepository = new ProductRepositoryDecorator(new FakeProductRepository);
    }
}
