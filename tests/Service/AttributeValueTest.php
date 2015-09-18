<?php
namespace inklabs\kommerce\Service;

use inklabs\kommerce\Entity;
use inklabs\kommerce\tests\Helper;
use inklabs\kommerce\tests\Helper\EntityRepository\FakeAttributeValueRepository;

class AttributeValueTest extends Helper\DoctrineTestCase
{
    /** @var FakeAttributeValueRepository */
    protected $attributeValueRepository;

    /** @var AttributeValue */
    protected $attributeValueService;

    public function setUp()
    {
        $this->attributeValueRepository = new FakeAttributeValueRepository;
        $this->attributeValueService = new AttributeValue($this->attributeValueRepository);
    }

    public function testFind()
    {
        $attributeValue = $this->attributeValueService->find(1);
        $this->assertTrue($attributeValue instanceof Entity\AttributeValue);
    }

    public function testFindMissing()
    {
        $this->attributeValueRepository->setReturnValue(null);

        $attributeValue = $this->attributeValueService->find(1);
        $this->assertSame(null, $attributeValue);
    }

    public function testGetAttributeValuesByIds()
    {
        $attributeValues = $this->attributeValueService->getAttributeValuesByIds([1]);
        $this->assertTrue($attributeValues[0] instanceof Entity\AttributeValue);
    }
}
