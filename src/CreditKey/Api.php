<?php

    namespace CreditKey;

    final class Api
    {
        public static function post($url, $publicKey, $sharedSecret, $args)
        {
            if (!extension_loaded('curl'))
            {
                throw new Exception('php_curl extension is required');
            }

            $fullArgs = array_merge($args,
                array(
                    'public_key' => $publicKey,
                    'shared_secret' => $sharedSecret
                ));
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fullArgs);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // TODO: dont do this
            $result = curl_exec($curl);
            return json_decode($result);
        }
    }

?>
