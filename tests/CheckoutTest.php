<?php
    use CreditKey\TestSupport\CreditKeyTestCase;

    final class CheckoutTest extends CreditKeyTestCase
    {
        public function testIsDisplayedInCheckout()
        {
            $cartContents = \CreditKey\TestSupport\CreditKeyTestData::cartContents();
            $customerId = (string) rand();

            $this->assertTrue(\CreditKey\Checkout::isDisplayedInCheckout($cartContents, $customerId));
        }

        public function testBeginCheckout()
        {
            $cartContents = \CreditKey\TestSupport\CreditKeyTestData::cartContents();
            $billingAddress = \CreditKey\TestSupport\CreditKeyTestData::billingAddress();
            $shippingAddress = \CreditKey\TestSupport\CreditKeyTestData::shippingAddress();
            $charges = \CreditKey\TestSupport\CreditKeyTestData::charges();
            $remoteId = rand();
            $customerId = rand();
            $returnUrl = 'http://www.myteststore.com/return_path_here';
            $cancelUrl = 'http://www.myteststore.com/cancel_path_here';

            $customerCheckoutUrl = \CreditKey\Checkout::beginCheckout($cartContents,
                $billingAddress, $shippingAddress, $charges, $remoteId, $customerId,
                $returnUrl, $cancelUrl);
            $this->assertNotFalse(filter_var($customerCheckoutUrl, FILTER_VALIDATE_URL));
        }

        public function testCompleteCheckout()
        {
            $ckOrderId = \CreditKey\TestSupport\CreditKeyTestData::createCompletedApplication();
            $this->assertTrue(\CreditKey\Checkout::completeCheckout($ckOrderId));
        }
    }
?>
