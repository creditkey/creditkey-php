<?php
    namespace CreditKey;
    use CreditKey\CartContents;
    use CreditKey\Models\Order;

    final class Orders
    {
        public static function confirm($ckOrderId, $merchantOrderId, $merchantOrderStatus, $cartContents, $charges)
        {
            $result = \CreditKey\Api::post('/ecomm/confirm_order',
                array(
                    'id' => $ckOrderId,
                    'merchant_order_id' => $merchantOrderId,
                    'merchant_status' => $merchantOrderStatus,
                    'cart_contents' => CartContents::buildFormCartItems($cartContents),
                    'charges' => $charges->toFormData()
                ));
            return Order::fromServiceData($result);
        }

        public static function update($ckOrderId, $merchantOrderStatus, $cartContents, $charges, $shippingAddress)
        {
            $formData = array(
                'id' => $ckOrderId,
                'merchant_status' => $merchantOrderStatus
            );

            if (!is_null($cartContents))
                $formData['cart_items'] = CartContents::buildFormCartItems($cartContents);

            if (!is_null($charges))
                $formData['charges'] = $charges->toFormData();

            if (!is_null($shippingAddress))
                $formData['shipping_address'] = $shippingAddress->toFormData();

            $result = \CreditKey\Api::post('/ecomm/update_order', $formData);
            return Order::fromServiceData($result);
        }

        public static function find($ckOrderId)
        {
            $result = \CreditKey\Api::get('/ecomm/find_order', array('id' => $ckOrderId));
            return Order::fromServiceData($result);
        }

        public static function findByMerchantOrderId($merchantOrderId)
        {
            $result = \CreditKey\Api::get('/ecomm/find_order_by_merchant_order_id',
                array('merchant_order_id' => $merchantOrderId));
            return Order::fromServiceData($result);
        }

        public static function cancel($ckOrderId)
        {
            $result = \CreditKey\Api::post('/ecomm/cancel_order', array('id' => $ckOrderId));
            return Order::fromServiceData($result);
        }

        public static function refund($ckOrderId, $refundAmount)
        {
            $result = \CreditKey\Api::post('/ecomm/refund', array(
                'id' => $ckOrderId,
                'amount' => $refundAmount
            ));
            return Order::fromServiceData($result);
        }
    }
?>
