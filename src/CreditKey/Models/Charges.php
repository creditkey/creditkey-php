<?php

namespace CreditKey\Models;

class Charges
{
    protected $total;
    protected $shipping;
    protected $tax;
    protected $grandTotal;

    public function getTotal()
    {
        return $total;
    }

    public function setTotal($setTotal)
    {
        $total = $setTotal;
    }

    public function getShipping()
    {
        return $shipping;
    }

    public function setShipping($setShipping)
    {
        $shipping = $setShipping;
    }

    public function getTax()
    {
        return $tax;
    }

    public function setTax($setTax)
    {
        $tax = $setTax;
    }

    public function getGrandTotal()
    {
        return $grandTotal;
    }

    public function setGrandTotal($setGrandTotal)
    {
        $grandTotal = $setGrandTotal;
    }
}

?>
