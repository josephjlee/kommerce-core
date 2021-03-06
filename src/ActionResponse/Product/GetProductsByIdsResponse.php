<?php
namespace inklabs\kommerce\ActionResponse\Product;

use inklabs\kommerce\EntityDTO\Builder\ProductDTOBuilder;
use inklabs\kommerce\EntityDTO\ProductDTO;
use inklabs\kommerce\Lib\PricingInterface;
use inklabs\kommerce\Lib\Query\ResponseInterface;

final class GetProductsByIdsResponse implements ResponseInterface
{
    /** @var ProductDTOBuilder[] */
    private $productDTOBuilders = [];

    /** @var PricingInterface */
    private $pricing;

    public function __construct(PricingInterface $pricing)
    {
        $this->pricing = $pricing;
    }

    public function addProductDTOBuilder(ProductDTOBuilder $productDTOBuilder): void
    {
        $this->productDTOBuilders[] = $productDTOBuilder;
    }

    /**
     * @return ProductDTO[]
     */
    public function getProductDTOs(): array
    {
        $productDTOs = [];
        foreach ($this->productDTOBuilders as $productDTOBuilder) {
            $productDTOs[] = $productDTOBuilder
                ->withPrice($this->pricing)
                ->build();
        }
        return $productDTOs;
    }
}
