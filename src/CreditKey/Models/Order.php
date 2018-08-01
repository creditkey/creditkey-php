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
        return $orderId;
    }

    public function setOrderId($setOrderId)
    {
        $orderId = $setOrderId;
    }

    public function getStatus()
    {
        return $status;
    }

    public function setStatus($setStatus)
    {
        $status = $setStatus;
    }

    public function getAmount()
    {
        return $amount;
    }

    public function setAmount($setAmount)
    {
        $amount = $setAmount;
    }

    public function getOriginalAmount()
    {
        return $originalAmount;
    }

    public function setOriginalAmount($setOriginalAmount)
    {
        $originalAmount = $setOriginalAmount;
    }

    public function getRefundAmount()
    {
        return $refundAmount;
    }

    public function setRefundAmount($setRefundAmount)
    {
        $refundAmount = $setRefundAmount;
    }

    public function getItems()
    {
        return $items;
    }

    public function setItems($setItems)
    {
        $items = $setItems;
    }
}

?>
