<?php
    namespace CreditKey;
    use CreditKey\Models\CartItem;

    final class CartContents
    {
        public static function buildFormCartItems($cartContents)
        {
            if ($cartContents == null)
            {
                return null;
            }

            $buildFormData = function($cartItem) {
                return $cartItem->toFormData();
            };

            return array_map($buildFormData, $cartContents);
        }

        public static function buildFromServiceData($dataItems)
        {
            $buildCartItem = function($data) {
                return CartItem::fromServiceData($data);
            };

            return array_map($buildCartItem, $dataItems);
        }
    }
?>
