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

        public function testUpdateOrder()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $merchantOrderStatus = 'test_status';
            $merchantOrderId = (string) rand();
            $charges = new \CreditKey\Models\Charges(99.99, 9.99, 9.99, 0, 119.97);
            $shippingAddress = new \CreditKey\Models\Address('Test', 'Tester', null, 'testtester@creditkey.com',
                '100 Main Street', 'Apt A', 'New York', 'NY', '10017', '212-555-1212');
            $cartContents = \CreditKey\TestSupport\CreditKeyTestData::cartContents();
            $newCartItem = new \CreditKey\Models\CartItem('999999', 'Test Adapter', 10.99, '9898928232', 1, null, null);
            array_push($cartContents, $newCartItem);

            $order = \CreditKey\Orders::update($ckOrderId, $merchantOrderStatus, $merchantOrderId, $cartContents, $charges, $shippingAddress);

            $this->assertEquals($merchantOrderStatus,
                $order->getMerchantStatus());
            $this->assertEquals($charges->getGrandTotal(),
                $order->getAmount());
            $this->assertEquals($shippingAddress->getEmail(),
                $order->getShippingAddress()->getEmail());
            $this->assertEquals($merchantOrderId,
                $order->getMerchantOrderId());

            $updatedCartItems = $order->getItems();
            $this->assertEquals($newCartItem->getMerchantId(),
                end($updatedCartItems)->getMerchantId());
        }

        public function testRegularFindOrder()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $order = \CreditKey\Orders::find($ckOrderId);
            $this->assertInstanceOf(\CreditKey\Models\Order::class, $order);
            $this->assertNotEmpty($order->getOrderId());
            $this->assertNotEmpty($order->getStatus());
            $this->assertNotEmpty($order->getShippingAddress()->getFirstName());
        }

        public function testFindOrderByMerchantId()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $originalOrder = \CreditKey\Orders::find($ckOrderId);

            $order = \CreditKey\Orders::findByMerchantOrderId($originalOrder->getMerchantOrderId());
            $this->assertInstanceOf(\CreditKey\Models\Order::class, $order);
            $this->assertNotEmpty($order->getOrderId());
            $this->assertNotEmpty($order->getStatus());
        }

        /**
         * @expectedException \CreditKey\Exceptions\NotFoundException
         */
        public function testOrderNotFoundException()
        {
            \CreditKey\Orders::find('abcdefg');
        }

        public function testCancel()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $order = \CreditKey\Orders::cancel($ckOrderId);
            $this->assertEquals('canceled', $order->getStatus());
        }

        public function testRefund()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::confirmedOrder();
            $originalOrder = \CreditKey\Orders::find($ckOrderId);
            $refundAmount = 10.00;

            $order = \CreditKey\Orders::refund($ckOrderId, $refundAmount);

            $this->assertEquals($refundAmount,
                $order->getRefundedAmount());
        }

        /**
         * @expectedException \CreditKey\Exceptions\InvalidRequestException
         */
        public function testExceptionThrownFindWithoutId()
        {
            \CreditKey\Orders::find(null);
        }
    }
?>
