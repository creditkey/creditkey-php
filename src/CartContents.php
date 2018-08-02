<?php
    namespace CreditKey;

    final class CartContents
    {
        public static function buildFormCartItems($cartContents)
        {
            if ($cartContents == null)
            {
                return null;
            }

            $getFormData = function($cartItem) {
                return $cartItem->toFormData();
            };

            return array_map($getFormData, $cartContents);
        }
    }
?>
