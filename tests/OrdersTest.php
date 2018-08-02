<?php
    use CreditKey\TestSupport\CreditKeyTestCase;
    use CreditKey\Orders;

    final class OrdersTest extends CreditKeyTestCase
    {
        public function testConfirm()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $merchantOrderId = (string) rand();
            $merchantOrderStatus = 'pending';
            $cartContents = \CreditKey\TestSupport\CreditKeyTestData::cartContents();
            $charges = \CreditKey\TestSupport\CreditKeyTestData::charges();

            $order = \CreditKey\Orders::confirm($ckOrderId, $merchantOrderId, $merchantOrderStatus,
                $cartContents, $charges);

            $this->assertEquals('captured', $order->getCaptureStatus());
        }

        public function testFindOrder()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $order = \CreditKey\Orders::find($ckOrderId);
            $this->assertInstanceOf(\CreditKey\Models\Order::class, $order);
            $this->assertNotEmpty($order->getOrderId());
            $this->assertNotEmpty($order->getStatus());
        }

        public function testFindOrderByMerchantId()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::confirmedOrder();
            $originalOrder = \CreditKey\Orders::find($ckOrderId);

            $order = \CreditKey\Orders::findByMerchantOrderId($originalOrder->getMerchantOrderId());
            $this->assertInstanceOf(\CreditKey\Models\Order::class, $order);
            $this->assertNotEmpty($order->getOrderId());
            $this->assertNotEmpty($order->getStatus());
        }

        public function testCancel()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $order = \CreditKey\Orders::cancel($ckOrderId);
            $this->assertEquals('canceled', $order->getStatus());
        }

        public function testRefund()
        {
            $this->assertEquals(true,
                \CreditKey\Orders::refund(null, null));
        }
    }
?>
