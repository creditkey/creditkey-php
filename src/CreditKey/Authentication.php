<?php
    namespace CreditKey;

    use \CreditKey\Api;

    final class Authentication
    {
        public static function authenticate($endpoint, $publicKey, $sharedSecret, $isProduction)
        {
            $result = \CreditKey\Api::post($endpoint . "/api/authenticate.json", $publicKey, $sharedSecret,
                array(
                    'is_production' => $isProduction
                ));
            return $result->success;
        }
    }

?>
