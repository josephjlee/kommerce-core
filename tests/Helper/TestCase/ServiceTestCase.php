<?php
namespace inklabs\kommerce\tests\Helper\TestCase;

use inklabs\kommerce\EntityDTO\OrderAddressDTO;
use inklabs\kommerce\Lib\CartCalculatorInterface;
use inklabs\kommerce\Lib\Event\EventDispatcher;
use inklabs\kommerce\Lib\Event\EventDispatcherInterface;
use inklabs\kommerce\Lib\PaymentGateway\FakePaymentGateway;
use inklabs\kommerce\Lib\PaymentGateway\PaymentGatewayInterface;
use inklabs\kommerce\Lib\ShipmentGateway\ShipmentGatewayInterface;
use inklabs\kommerce\Lib\FileManagerInterface;
use inklabs\kommerce\Service\ServiceFactory;
use inklabs\kommerce\tests\Helper\Lib\FakeFileManager;
use inklabs\kommerce\tests\Helper\Lib\ShipmentGateway\FakeShipmentGateway;
use inklabs\kommerce\tests\Helper\Service\MockService;

abstract class ServiceTestCase extends EntityRepositoryTestCase
{
    /** @var MockService */
    protected $mockService;

    public function setUp()
    {
        parent::setUp();
        $this->mockService = new MockService($this->dummyData);
    }

    protected function getEventDispatcher()
    {
        return new EventDispatcher;
    }

    protected function getServiceFactory(
        CartCalculatorInterface $cartCalculator = null,
        EventDispatcherInterface $eventDispatcher = null,
        PaymentGatewayInterface $paymentGateway = null,
        ShipmentGatewayInterface $shipmentGateway = null,
        FileManagerInterface $fileManager = null
    ) {
        if ($cartCalculator === null) {
            $cartCalculator = $this->getCartCalculator();
        }

        if ($eventDispatcher === null) {
            $eventDispatcher = new EventDispatcher;
        }

        if ($paymentGateway === null) {
            $paymentGateway = $this->getPaymentGateway();
        }

        if ($fileManager === null) {
            $fileManager = $this->getFileManager();
        }

        if ($shipmentGateway === null) {
            $fromAddress = new OrderAddressDTO;
            $fromAddress->company = 'Acme Co.';
            $fromAddress->address1 = '123 Any St';
            $fromAddress->address2 = 'Ste 3';
            $fromAddress->city = 'Santa Monica';
            $fromAddress->state = 'CA';
            $fromAddress->zip5 = '90401';
            $fromAddress->phone = '555-123-4567';

            $shipmentGateway = new FakeShipmentGateway($fromAddress);
        }

        return new ServiceFactory(
            $this->getRepositoryFactory(),
            $cartCalculator,
            $eventDispatcher,
            $paymentGateway,
            $shipmentGateway,
            $fileManager
        );
    }

    protected function getPaymentGateway()
    {
        return new FakePaymentGateway;
    }

    private function getFileManager()
    {
        return new FakeFileManager;
    }
}
