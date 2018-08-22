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

        public static function get($url, $args)
        {
            if (!self::$isConfigured)
            {
                throw new \CreditKey\Exceptions\ApiNotConfiguredException();
            }

            $requestArgs = self::requestArgs($args);
            $fullUrl = self::$apiUrlBase . $url . '?' . http_build_query($requestArgs);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $fullUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = self::executeAndDecode($curl);
            return $result;
        }

        public static function post($url, $args)
        {
            if (!self::$isConfigured)
            {
                throw new \CreditKey\Exceptions\ApiNotConfiguredException();
            }

            $json = json_encode(self::requestArgs($args));

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
            $result = self::executeAndDecode($curl);
            return $result;
        }

        private static function requestArgs($args)
        {
            $auth = array(
                'public_key' => self::$publicKey,
                'shared_secret' => self::$sharedSecret
            );

            return isset($args)
                ? array_merge($args, $auth)
                : $auth;
        }

        private static function executeAndDecode($curl)
        {
            $response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

            if ($status == 404)
                throw new \CreditKey\Exceptions\NotFoundException();
            else if ($status == 400)
                throw new \CreditKey\Exceptions\InvalidRequestException();
            else if ($status != 200)
                throw new \CreditKey\Exceptions\OperationErrorException();

            // fwrite(STDERR, print_r($result, true));
            return json_decode($response);
        }
    }
?>
