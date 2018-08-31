<?php
    use CreditKey\TestSupport\CreditKeyTestCase;

    final class AuthenticationTest extends CreditKeyTestCase
    {
        public function testCanAuthenticate()
        {
            $this->assertTrue(\CreditKey\Authentication::authenticate());
        }

        public function testInvalidAuthFails()
        {
            $config = \CreditKey\TestSupport\CreditKeyTestData::getApiConfiguration();

            /* load invalid credential */
            \CreditKey\Api::configure($config->api_url, 'invalid', 'credential');

            $authResult = \CreditKey\Authentication::authenticate();

            /* replace the correct credential */
            \CreditKey\Api::configure($config->api_url, $config->public_key,
                $config->shared_secret);

            /* perform the assertion */
            $this->assertFalse($authResult);
        }
    }
?>
