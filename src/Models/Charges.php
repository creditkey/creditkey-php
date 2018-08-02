<?php
    namespace CreditKey\Models;

    class Charges
    {
        protected $total;
        protected $shipping;
        protected $tax;
        protected $grandTotal;

        function __construct($total, $shipping, $tax, $grandTotal)
        {
            $this->total = $total;
            $this->shipping = $shipping;
            $this->tax = $tax;
            $this->grandTotal = $grandTotal;
        }

        public function getTotal()
        {
            return $this->total;
        }

        public function getShipping()
        {
            return $this->shipping;
        }

        public function getTax()
        {
            return $this->tax;
        }

        public function getGrandTotal()
        {
            return $this->grandTotal;
        }

        public function toFormData()
        {
            return array(
                'total' => $this->total,
                'shipping' => $this->shipping,
                'tax' => $this->tax,
                'grand_total' => $this->grandTotal
            );
        }
    }
?>
