<?php
    namespace CreditKey;
    use CreditKey\Models\Order;

    final class Orders
    {
        public static function confirm($ckOrderId, $merchantOrderId, $merchantOrderStatus, $cartContents, $charges)
        {
            $result = \CreditKey\Api::post('/order/confirm',
                array(
                    'id' => $ckOrderId,
                    'merchant_order_id' => $merchantOrderId,
                    'merchant_status' => $merchantOrderStatus,
                    'cart_contents' => \CreditKey\CartContents::buildFormCartItems($cartContents),
                    'charges' => $charges->toFormData()
                ));
            return Order::fromServiceData($result);
        }

        public static function update($ckOrderId, $cartContents, $charges, $merchantOrderStatus)
        {

        }

        public static function find($ckOrderId)
        {
            $result = \CreditKey\Api::get('/order/find', array('id' => $ckOrderId));
            return Order::fromServiceData($result);
        }

        public static function findByMerchantOrderId($merchantOrderId)
        {
            $result = \CreditKey\Api::get('/order/find_by_merchant_order_id',
                array('merchant_order_id' => $merchantOrderId));
            return Order::fromServiceData($result);
        }

        public static function cancel($ckOrderId)
        {
            $result = \CreditKey\Api::post('/order/cancel', array('id' => $ckOrderId));
            return Order::fromServiceData($result);
        }

        public static function refund($ckOrderId, $refundAmount)
        {

        }    
    }
?>
