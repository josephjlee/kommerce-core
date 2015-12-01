<?php
namespace inklabs\kommerce\Entity;

use DateTime;
use inklabs\kommerce\tests\Helper\DoctrineTestCase;

class InventoryTransactionTest extends DoctrineTestCase
{
    public function testPickAndShipTransaction()
    {
        $warehouse = $this->dummyData->getWarehouse();
        $product = $this->dummyData->getProduct();
        $widgetBin = new InventoryLocation($warehouse, 'Widget Bin', 'Z1-A13-B37-L5-P3');
        $customerLocation = new InventoryLocation($warehouse, 'Shipped to Customer', 'SHIP');

        $pickTransaction = new InventoryTransaction($widgetBin);
        $pickTransaction->setProduct($product);
        $pickTransaction->setDebitQuantity(2);
        $pickTransaction->setMemo('Picked 2 Widgets');

        $shipTransaction = new InventoryTransaction($customerLocation);
        $shipTransaction->setProduct($product);
        $shipTransaction->setCreditQuantity(2);
        $shipTransaction->setMemo('Shipped 2 Widgets to customer');

        $this->assertEntityValid($pickTransaction);
        $this->assertEntityValid($shipTransaction);
        $this->assertTrue($pickTransaction->getInventoryLocation() instanceof InventoryLocation);
        $this->assertTrue($pickTransaction->getProduct() instanceof Product);
        $this->assertTrue($pickTransaction->getCreated() instanceof DateTime);
        $this->assertSame(2, $pickTransaction->getDebitQuantity());
        $this->assertSame(null, $pickTransaction->getCreditQuantity());
        $this->assertSame('Picked 2 Widgets', $pickTransaction->getMemo());
        $this->assertSame(InventoryTransaction::TYPE_MOVE, $pickTransaction->getType());
        $this->assertSame('Move', $pickTransaction->getTypeText());

        $this->assertSame(null, $shipTransaction->getDebitQuantity());
        $this->assertSame(2, $shipTransaction->getCreditQuantity());
    }

    public function testBothDebitAndCreditCannotBeNull()
    {
        $inventoryTransaction = $this->dummyData->getInventoryTransaction();
        $inventoryTransaction->setDebitQuantity(null);
        $inventoryTransaction->setCreditQuantity(null);

        $this->assertInvalidQuantity($inventoryTransaction);
    }

    public function testDebitOrCreditMustBeNull()
    {
        $inventoryTransaction = $this->dummyData->getInventoryTransaction();
        $inventoryTransaction->setDebitQuantity(2);
        $inventoryTransaction->setCreditQuantity(2);

        $this->assertInvalidQuantity($inventoryTransaction);
    }

    protected function assertInvalidQuantity(InventoryTransaction $inventoryTransaction)
    {
        $errors = $this->getValidationErrors($inventoryTransaction);

        $this->assertSame(2, count($errors));
        $this->assertSame('debitQuantity', $errors->get(0)->getPropertyPath());
        $this->assertSame('Only DebitQuantity or CreditQuantity should be set', $errors->get(0)->getMessage());

        $this->assertSame('creditQuantity', $errors->get(1)->getPropertyPath());
        $this->assertSame('Only DebitQuantity or CreditQuantity should be set', $errors->get(1)->getMessage());
    }

    public function testHoldInventoryForOrderShipment()
    {
        $warehouse = $this->dummyData->getWarehouse();
        $product = $this->dummyData->getProduct();

        $widgetBinLocation = $this->dummyData->getInventoryLocation($warehouse);
        $debitTransaction = new InventoryTransaction($widgetBinLocation, InventoryTransaction::TYPE_HOLD);
        $debitTransaction->setProduct($product);
        $debitTransaction->setDebitQuantity(2);
        $debitTransaction->setMemo('Hold 2 Widgets for order #123');

        $customerHoldingLocation = new InventoryLocation($warehouse, 'Reserve for Customer', 'HOLD');
        $creditTransaction = new InventoryTransaction($customerHoldingLocation, InventoryTransaction::TYPE_HOLD);
        $creditTransaction->setProduct($product);
        $creditTransaction->setCreditQuantity(2);
        $creditTransaction->setMemo('Hold 2 Widgets for order #123');

        $this->assertEntityValid($debitTransaction);
        $this->assertEntityValid($creditTransaction);
        $this->assertSame(InventoryTransaction::TYPE_HOLD, $debitTransaction->getType());
        $this->assertSame('Hold', $debitTransaction->getTypeText());
    }

    public function testMoveInventoryBetweenLocations()
    {
        $warehouse = $this->dummyData->getWarehouse();
        $product = $this->dummyData->getProduct();
        $sourceLocation = new InventoryLocation($warehouse, 'Source Location', 'S1');
        $destinationLocation = new InventoryLocation($warehouse, 'Destination Location', 'D1');

        $debitTransaction = new InventoryTransaction($sourceLocation);
        $debitTransaction->setProduct($product);
        $debitTransaction->setDebitQuantity(2);
        $debitTransaction->setMemo('Move 2 Widgets');

        $creditTransaction = new InventoryTransaction($destinationLocation);
        $creditTransaction->setProduct($product);
        $creditTransaction->setCreditQuantity(2);
        $creditTransaction->setMemo('Move 2 Widgets');

        $this->assertEntityValid($debitTransaction);
        $this->assertEntityValid($creditTransaction);
    }

    public function testInventoryTransactionFailsIfMoveTypeAndMissingInventoryLocation()
    {
        $product = $this->dummyData->getProduct();
        $inventoryTransaction = new InventoryTransaction;
        $inventoryTransaction->setProduct($product);
        $inventoryTransaction->setDebitQuantity(2);
        $inventoryTransaction->setMemo('Move 2 Widgets');

        $errors = $this->getValidationErrors($inventoryTransaction);

        $this->assertSame(1, count($errors));
        $this->assertSame('inventoryLocation', $errors->get(0)->getPropertyPath());
        $this->assertSame('InventoryLocation must be set for Move operation', $errors->get(0)->getMessage());
    }
}
