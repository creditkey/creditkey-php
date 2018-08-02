<?php
    namespace CreditKey\Models;

    class Order
    {
        protected $orderId;
        protected $status;
        protected $amount;
        protected $originalAmount;
        protected $refundAmount;
        protected $items;

        public function getOrderId()
        {
            return $this->orderId;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function getAmount()
        {
            return $this->amount;
        }

        public function getOriginalAmount()
        {
            return $this->originalAmount;
        }

        public function getRefundAmount()
        {
            return $this->refundAmount;
        }

        public function getItems()
        {
            return $this->items;
        }
    }
?>
