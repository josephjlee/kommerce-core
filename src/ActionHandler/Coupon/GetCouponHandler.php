<?php
namespace inklabs\kommerce\ActionHandler\Coupon;

use inklabs\kommerce\Action\Coupon\GetCouponQuery;
use inklabs\kommerce\EntityDTO\Builder\DTOBuilderFactoryInterface;
use inklabs\kommerce\EntityRepository\CouponRepositoryInterface;
use inklabs\kommerce\Lib\Authorization\AuthorizationContextInterface;
use inklabs\kommerce\Lib\Query\QueryHandlerInterface;

final class GetCouponHandler implements QueryHandlerInterface
{
    /** @var GetCouponQuery */
    private $query;

    /** @var CouponRepositoryInterface */
    private $couponRepository;

    /** @var DTOBuilderFactoryInterface */
    private $dtoBuilderFactory;

    public function __construct(
        GetCouponQuery $query,
        CouponRepositoryInterface $couponRepository,
        DTOBuilderFactoryInterface $dtoBuilderFactory
    ) {
        $this->query = $query;
        $this->couponRepository = $couponRepository;
        $this->dtoBuilderFactory = $dtoBuilderFactory;
    }

    public function verifyAuthorization(AuthorizationContextInterface $authorizationContext)
    {
        $authorizationContext->verifyCanMakeRequests();
    }

    public function handle()
    {
        $coupon = $this->couponRepository->findOneById(
            $this->query->getRequest()->getCouponId()
        );

        $this->query->getResponse()->setCouponDTOBuilder(
            $this->dtoBuilderFactory->getCouponDTOBuilder($coupon)
        );
    }
}
