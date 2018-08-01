<?php

namespace CreditKey\Models;

class CartItem
{
    protected $merchantProductId;
    protected $name;
    protected $price;
    protected $sku;
    protected $quantity;

    public function getMerchantId()
    {
        return $merchantProductId;
    }

    public function setMerchantId($setId)
    {
        $merchantProductId = $setId;
    }

    public function getName()
    {
        return $name;
    }

    public function setName($setName)
    {
        $name = $setName;
    }

    public function getPrice()
    {
        return $price;
    }

    public function setPrice($setPrice)
    {
        $price = $setPrice;
    }

    public function getSku()
    {
        return $sku;
    }

    public function setSku($setSku)
    {
        $sku = $setSku;
    }

    public function getQuantity()
    {
        return $quantity;
    }

    public function setQuantity($setQuantity)
    {
        $quantity = $setQuantity;
    }
}

?>
