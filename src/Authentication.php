<?php
    namespace CreditKey;
    use \CreditKey\Api;

    final class Authentication
    {
        public static function authenticate()
        {
            $result = \CreditKey\Api::post('/ecomm/authenticate.json', null);
            return $result->success;
        }
    }
?>
