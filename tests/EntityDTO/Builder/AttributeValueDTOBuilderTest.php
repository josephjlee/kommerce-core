<?php
namespace inklabs\kommerce\EntityDTO;

use inklabs\kommerce\tests\Helper\TestCase\EntityDTOBuilderTestCase;

class AttributeValueDTOBuilderTest extends EntityDTOBuilderTestCase
{
    public function testBuild()
    {
        $attributeValue = $this->dummyData->getAttributeValue();
        $productAttribute = $this->dummyData->getProductAttribute(null, $attributeValue);

        $attributeValueDTO = $this->getDTOBuilderFactory()
            ->getAttributeValueDTOBuilder($attributeValue)
            ->withAllData()
            ->build();

        $this->assertTrue($attributeValueDTO instanceof AttributeValueDTO);
        $this->assertTrue($attributeValueDTO->attribute instanceof AttributeDTO);
        $this->assertTrue($attributeValueDTO->productAttributes[0] instanceof ProductAttributeDTO);
        $this->assertTrue($attributeValueDTO->productAttributes[0]->product instanceof ProductDTO);
    }
}
