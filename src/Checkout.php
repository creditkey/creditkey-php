<?php
    namespace CreditKey;
    use \CreditKey\Api;

    final class Checkout
    {
        public static function isDisplayedInCheckout($cartContents, $customerId)
        {
        }

        public static function beginCheckout($cartContents, $billingAddress, $shippingAddress,
            $charges, $remoteId, $customerId, $returnUrl, $cancelUrl)
        {
            $getFormData = function($cartItem) {
                return $cartItem->toFormData();
            };

            $formData = array(
                'cart_items' => array_map($getFormData, $cartContents),
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

        }
    }
?>
