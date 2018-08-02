<?php
    namespace CreditKey;
    use \CreditKey\Api;

    final class Checkout
    {
        private static function buildFormCartItems($cartContents)
        {
            $getFormData = function($cartItem) {
                return $cartItem->toFormData();
            };

            return array_map($getFormData, $cartContents);
        }

        public static function isDisplayedInCheckout($cartContents, $customerId)
        {
            $result = \CreditKey\Api::post('/ecomm/is_displayed_in_checkout',
                array(
                    'cart_items' => self::buildFormCartItems($cartContents),
                    'remote_customer_id' => $customerId
                ));
            return $result->is_displayed_in_checkout;
        }

        public static function beginCheckout($cartContents, $billingAddress, $shippingAddress,
            $charges, $remoteId, $customerId, $returnUrl, $cancelUrl)
        {
            $formData = array(
                'cart_items' => self::buildFormCartItems($cartContents),
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
