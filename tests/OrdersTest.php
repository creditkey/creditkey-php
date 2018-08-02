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

            $this->assertTrue(
                \CreditKey\Orders::confirm($ckOrderId, $merchantOrderId, $merchantOrderStatus,
                    $cartContents, $charges));
        }

        public function testFind()
        {
            $this->assertEquals(true,
                \CreditKey\Orders::find(null));
        }

        public function testCancel()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::newOrder();
            $this->assertTrue(\CreditKey\Orders::cancel($ckOrderId));
        }

        public function testRefund()
        {
            $this->assertEquals(true,
                \CreditKey\Orders::refund(null, null));
        }
    }
?>
