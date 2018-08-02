<?php
    namespace CreditKey;

    final class Api
    {
        private static $isConfigured = false;
        private static $apiUrlBase;
        private static $publicKey;
        private static $sharedSecret;

        public static function configure($apiUrlBase, $publicKey, $sharedSecret)
        {
            self::$apiUrlBase = $apiUrlBase;
            self::$publicKey = $publicKey;
            self::$sharedSecret = $sharedSecret;
            self::$isConfigured = true;
        }

        public static function isConfigured()
        {
            return self::$isConfigured;
        }

        public static function post($url, $args)
        {
            if (!self::$isConfigured)
            {
                throw new Exception('Credit Key API is not configured');
            }

            if (!extension_loaded('curl'))
            {
                throw new Exception('php_curl extension is required');
            }

            $auth = array(
                'public_key' => self::$publicKey,
                'shared_secret' => self::$sharedSecret
            );

            $fullArgs = isset($args)
                ? array_merge($args, $auth)
                : $auth;
            $json = json_encode($fullArgs);

            // fwrite(STDERR, print_r($fullArgs, true));
            $fullUrl = self::$apiUrlBase . $url;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
            curl_setopt($curl, CURLOPT_URL, $fullUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json)
            ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // TODO: dont do this
            $result = curl_exec($curl);

            return json_decode($result);
        }
    }
?>
