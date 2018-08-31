<?php
    namespace CreditKey;
    use \CreditKey\Api;

    final class Authentication
    {
        public static function authenticate()
        {
            try
            {
                $result = \CreditKey\Api::post('/ecomm/authenticate', null);
                return $result->success;
            }
            catch (\CreditKey\Exceptions\ApiUnauthorizedException $e)
            {
                return false;
            }
        }
    }
?>
