<?php
    use CreditKey\TestSupport\CreditKeyTestCase;

    final class CheckoutTest extends CreditKeyTestCase
    {
        public function testIsDisplayedInCheckout()
        {
            $this->assertEquals(true,
                \CreditKey\Checkout::isDisplayedInCheckout(null, null));
        }

        public function testBeginCheckout()
        {
            $cartContents = array(
                new \CreditKey\Models\CartItem('100010', 'Fun Squeegee', 15.99, '1020329892', 3, null, 'Orange'),
                new \CreditKey\Models\CartItem('102302', 'Jordache Mens Jean', 89.99, '1928392983', 1, '38', 'Black'),
                new \CreditKey\Models\CartItem('192832', 'Apple IIe Computer', 1923.82, '1029382923', 1, null, null)
            );
            $billingAddress = new \CreditKey\Models\Address('Joe', 'Buyer', 'joebuyer@creditkey.com',
                '700 E Ocean Blvd', 'Suite 2301', 'Long Beach', 'CA', '90802', '562-555-1212');
            $shippingAddress = new \CreditKey\Models\Address('Joe', 'Receiver', 'joerecevier@creditkey.com',
                '333 E 46th St', 'Apt 10E', 'New York', 'NY', '10017', '212-555-1212');
            $charges = new \CreditKey\Models\Charges(2061.78, 49.00, 105.53, 2216.31);
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
