<?php
    namespace CreditKey\Models;

    class CartItem
    {
        protected $merchantProductId;
        protected $name;
        protected $price;
        protected $sku;
        protected $quantity;
        protected $size;
        protected $color;

        function __construct($merchantProductId, $name, $price, $sku, $quantity, $size, $color)
        {
            $this->merchantProductId = $merchantProductId;
            $this->name = $name;
            $this->price = $price;
            $this->sku = $sku;
            $this->quantity = $quantity;
            $this->size = $size;
            $this->color = $color;
        }

        public function getMerchantId()
        {
            return $this->merchantProductId;
        }

        public function getName()
        {
            return $this->name;
        }

        public function getPrice()
        {
            return $this->price;
        }

        public function getSku()
        {
            return $this->sku;
        }

        public function getQuantity()
        {
            return $this->quantity;
        }

        public function getSize()
        {
            return $this->size;
        }

        public function getColor()
        {
            return $this->color;
        }

        public function toFormData()
        {
            return array(
                'merchant_id' => $this->merchantProductId,
                'name' => $this->name,
                'price' => $this->price,
                'sku' => $this->sku,
                'quantity' => $this->quantity,
                'size' => $this->size,
                'color' => $this->color
            );
        }
    }
?>
