<?php
namespace inklabs\kommerce\EntityRepository;

use inklabs\kommerce\Entity\Coupon;
use inklabs\kommerce\tests\Helper;

class CouponRepositoryTest extends Helper\DoctrineTestCase
{
    protected $metaDataClassNames = [
        'kommerce:Coupon',
    ];

    /** @var CouponRepositoryInterface */
    protected $couponRepository;

    public function setUp()
    {
        $this->couponRepository = $this->repository()->getCouponRepository();
    }

    private function setupCoupon()
    {
        $coupon = $this->getDummyCoupon();

        $this->entityManager->persist($coupon);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    public function testSave()
    {
        $coupon = $this->getDummyCoupon(1);

        $couponRepository = $this->couponRepository;
        $couponRepository->create($coupon);

        $coupon->setName('new name');
        $this->assertSame(null, $coupon->getUpdated());
        $couponRepository->save($coupon);
        $this->assertTrue($coupon->getUpdated() instanceof \DateTime);

        $this->couponRepository->remove($coupon);
        $this->assertSame(null, $coupon->getId());
    }

    public function testFind()
    {
        $this->setupCoupon();

        $coupon = $this->couponRepository->findOneById(1);

        $this->assertTrue($coupon instanceof Coupon);
    }

    public function testFindOneByCode()
    {
        $this->setupCoupon();

        $coupon = $this->couponRepository->findOneByCode('20PCT1');

        $this->assertTrue($coupon instanceof Coupon);
    }

    public function testGetAllCoupons()
    {
        $this->setupCoupon();

        $coupons = $this->couponRepository->getAllCoupons('Test');

        $this->assertTrue($coupons[0] instanceof Coupon);
    }

    public function testGetAllCouponsByIds()
    {
        $this->setupCoupon();

        $coupons = $this->couponRepository->getAllCouponsByIds([1]);

        $this->assertTrue($coupons[0] instanceof Coupon);
    }

    public function testCreate()
    {
        $coupon = $this->getDummyCoupon(1);

        $this->assertSame(null, $coupon->getId());
        $this->couponRepository->create($coupon);
        $this->assertSame(1, $coupon->getId());
    }
}
