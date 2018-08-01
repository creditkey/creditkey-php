<?php
    use PHPUnit\Framework\TestCase;
    use CreditKey\Checkout;

    final class CheckoutTest extends TestCase
    {
        public function testIsDisplayedInCheckout()
        {
            $this->assertEquals(true,
                \CreditKey\Checkout::isDisplayedInCheckout(null, null));
        }

        public function testBeginCheckout()
        {
            $this->assertEquals(true,
                \CreditKey\Checkout::beginCheckout(null, null, null, null, null, null, null, null));
        }

        public function testCompleteCheckout()
        {
            $this->assertEquals(true,
                \CreditKey\Checkout::completeCheckout(null));
        }
    }
?>
