<?php
    use PHPUnit\Framework\TestCase;
    use CreditKey\Authentication;

    final class AuthenticationTest extends TestCase
    {
        protected $apiUrlBase;
        protected $publicKey;
        protected $sharedSecret;

        protected function setUp()
        {
            // TODO: Support for an external API
            if (getenv('CREDITKEY_SOURCE_PATH') == false)
            {
                throw new Exception('Credit Key Source Path Missing');
            }
            else
            {
                $rake_output = shell_exec('cd ' . getenv('CREDITKEY_SOURCE_PATH') . ' && rake ck:test_support:sdk_authentication');
                $config = json_decode($rake_output);

                $this->apiUrlBase = $config->api_url;
                $this->publicKey = $config->public_key;
                $this->sharedSecret = $config->shared_secret;
            }
        }

        public function testCanAuthenticate()
        {
            $this->assertEquals(true,
                \CreditKey\Authentication::authenticate(
                    $this->apiUrlBase, $this->publicKey, $this->sharedSecret, false));
        }
    }
?>
