<?php
    namespace CreditKey;
    
    use PHPUnit\Framework\TestCase;

    abstract class CreditKeyTestCase extends TestCase
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
                $items = explode(',', $rake_output);

                $this->apiUrlBase = $items[0];
                $this->publicKey = $items[1];
                $this->sharedSecret = $items[2];
            }
        }
    }

?>
