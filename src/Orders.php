<?php
    namespace CreditKey;

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

            return $result->success;
        }

        public static function update($ckOrderId, $cartContents, $charges, $merchantOrderStatus)
        {

        }

        public static function find($ckOrderId)
        {

        }

        public static function findByMerchantOrderId($merchantOrderId)
        {

        }

        public static function cancel($ckOrderId)
        {
            $result = \CreditKey\Api::post('/order/cancel', array('id' => $ckOrderId));
            return $result->success;
        }

        public static function refund($ckOrderId, $refundAmount)
        {

        }    
    }
?>
