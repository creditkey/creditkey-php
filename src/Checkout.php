<?php
    namespace CreditKey;
    use \CreditKey\Api;

    final class Checkout
    {
        public static function isDisplayedInCheckout($cartContents, $customerId)
        {
            $result = \CreditKey\Api::post('/ecomm/is_displayed_in_checkout',
                array(
                    'cart_items' => \CreditKey\CartContents::buildFormCartItems($cartContents),
                    'remote_customer_id' => $customerId
                ));
            return $result->is_displayed_in_checkout;
        }

        public static function beginCheckout($cartContents, $billingAddress, $shippingAddress,
            $charges, $remoteId, $customerId, $returnUrl, $cancelUrl)
        {
            if (is_null($cartContents) || is_null($billingAddress) || is_null($shippingAddress)
                || is_null($charges) || is_null($remoteId) || is_null($returnUrl) || is_null($cancelUrl)) {
                throw new \CreditKey\Exceptions\InvalidRequestException();
            }

            $formData = array(
                'cart_items' => \CreditKey\CartContents::buildFormCartItems($cartContents),
                'shipping_address' => $shippingAddress->toFormData(),
                'billing_address' => $billingAddress->toFormData(),
                'charges' => $charges->toFormData(),
                'remote_id' => $remoteId,
                'remote_customer_id' => $customerId,
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl
            );

            $result = \CreditKey\Api::post('/ecomm/begin_checkout', $formData);
            return $result->checkout_url;
        }

        public static function completeCheckout($ckOrderId)
        {
            $result = \CreditKey\Api::post('/ecomm/complete_checkout', array('id' => $ckOrderId));
            return $result->success;
        }
    }
?>
