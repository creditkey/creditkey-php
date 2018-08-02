<?php
    namespace CreditKey\Models;

    class CustomerStatus
    {
        protected $merchantCustomerId;
        protected $hasActiveCreditLine;
        protected $cartContentsExceedCreditLimit;
        protected $availableCredit;

        public function getMerchantCustomerId()
        {
            return $this->merchantCustomerId;
        }

        public function getHasActiveCreditLine()
        {
            return $this->hasActiveCreditLine;
        }

        public function getCartContentsExceedCreditLimit()
        {
            return $this->cartContentsExceedCreditLimit;
        }

        public function getAvailableCredit()
        {
            return $this->availableCredit;
        }
    }
?>
