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
        return $merchantCustomerId;
    }

    public function setMerchantCustomerId($setMerchantCustomerId)
    {
        $merchantCustomerId = $setMerchantCustomerId;
    }

    public function getHasActiveCreditLine()
    {
        return $hasActiveCreditLine;
    }

    public function setHasActiveCreditLine($setHasActiveCreditLine)
    {
        $hasActiveCreditLine = $setHasActiveCreditLine;
    }

    public function getCartContentsExceedCreditLimit()
    {
        return $cartContentsExceedCreditLimit;
    }

    public function setCartContentsExceedCreditLimit($setCartContentsExceedCreditLimit)
    {
        $cartContentsExceedCreditLimit = $setCartContentsExceedCreditLimit;
    }

    public function getAvailableCredit()
    {
        return $availableCredit;
    }

    public function setAvailableCredit($setAvailableCredit)
    {
        $availableCredit = $setAvailableCredit;
    }
}

?>
