<?php
    namespace CreditKey\Models;
    use CreditKey\CartContents;
    use CreditKey\Models\Address;

    class Order
    {
        protected $orderId;
        protected $status;
        protected $captureStatus;
        protected $amount;
        protected $refundedAmount;
        protected $items;
        protected $merchantOrderId;
        protected $merchantStatus;
        protected $shippingAddress;

        function __construct($orderId, $status, $captureStatus, $amount, $refundedAmount,
            $items, $merchantOrderId, $merchantStatus, $shippingAddress)
        {
            $this->orderId = $orderId;
            $this->status = $status;
            $this->captureStatus = $captureStatus;
            $this->amount = $amount;
            $this->refundedAmount = $refundedAmount;
            $this->items = $items;
            $this->merchantOrderId = $merchantOrderId;
            $this->merchantStatus = $merchantStatus;
            $this->shippingAddress = $shippingAddress;
        }

        public static function fromServiceData($data)
        {
            $cartItems = CartContents::buildFromServiceData($data->items);

            $shippingAddress = new Address($data->shipping_first_name, $data->shipping_last_name,
                $data->shipping_email, $data->shipping_address1, $data->shipping_address2,
                $data->shipping_city, $data->shipping_state, $data->shipping_zip,
                $data->shipping_phone);

            return new Order($data->id, $data->status, $data->capture_status, $data->amount,
                $data->refunded_amount, $cartItems, $data->merchant_order_id,
                $data->merchant_status, $shippingAddress);
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

        public function getRefundedAmount()
        {
            return $this->refundedAmount;
        }

        public function getItems()
        {
            return $this->items;
        }

        public function getMerchantOrderId()
        {
            return $this->merchantOrderId;
        }

        public function getMerchantStatus()
        {
            return $this->merchantStatus;
        }

        public function getShippingAddress()
        {
            return $this->shippingAddress;
        }
    }
?>
