<?php
    use PHPUnit\Framework\TestCase;
    use CreditKey\Orders;

    final class OrdersTest extends TestCase
    {
        public function testConfirm()
        {
            $this->assertEquals(true,
                \CreditKey\Orders::confirm(null, null, null));
        }

        public function testFind()
        {
            $this->assertEquals(true,
                \CreditKey\Orders::find(null));
        }

        public function testCancel()
        {
            $this->assertEquals(true,
                \CreditKey\Orders::cancel(null));
        }

        public function testRefund()
        {
            $this->assertEquals(true,
                \CreditKey\Orders::refund(null, null));
        }
    }
?>
