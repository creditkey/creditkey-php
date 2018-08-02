<?php
    namespace CreditKey\Models;
    use CreditKey\CartContents;

    class Order
    {
        protected $orderId;
        protected $status;
        protected $captureStatus;
        protected $amount;
        protected $refundAmount;
        protected $items;
        protected $merchantOrderId;
        protected $merchantStatus;

        function __construct($orderId, $status, $captureStatus, $amount, $refundedAmount,
            $items, $merchantOrderId, $merchantStatus)
        {
            $this->orderId = $orderId;
            $this->status = $status;
            $this->captureStatus = $captureStatus;
            $this->amount = $amount;
            $this->refundedAmount = $refundedAmount;
            $this->items = $items;
            $this->merchantOrderId = $merchantOrderId;
            $this->merchantStatus = $merchantStatus;
        }

        public static function fromServiceData($data)
        {
            $cartItems = CartContents::buildFromServiceData($data->items);
            return new Order($data->id, $data->status, $data->capture_status, $data->amount,
                $data->refunded_amount, $cartItems, $data->merchant_order_id,
                $data->merchant_status);
        }

        public function getOrderId()
        {
            return $this->orderId;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function getCaptureStatus()
        {
            return $this->captureStatus;
        }

        public function getAmount()
        {
            return $this->amount;
        }

        public function getRefundAmount()
        {
            return $this->refundAmount;
        }

        public function getItems()
        {
            return $this->items;
        }

        public function getMerchantOrderId()
        {
            return $this->merchantOrderId;
        }

        public function getOrderStatus()
        {
            return $this->orderStatus;
        }
    }
?>
