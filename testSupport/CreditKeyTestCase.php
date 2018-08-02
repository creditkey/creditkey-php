<?php
    namespace CreditKey\TestSupport;

    use PHPUnit\Framework\TestCase;
    use CreditKey\Api;

    abstract class CreditKeyTestCase extends TestCase
    {
        public static function setUpBeforeClass()
        {
            if (\CreditKey\Api::isConfigured())
            {
                return;
            }

            // TODO: Support for an external API
            if (getenv('CREDITKEY_SOURCE_PATH') == false)
            {
                throw new Exception('Credit Key Source Path Missing');
            }
            else
            {
                $rake_output = shell_exec('cd ' . getenv('CREDITKEY_SOURCE_PATH') . ' && rake ck:test_support:sdk_authentication');
                $config = json_decode($rake_output);

                \CreditKey\Api::configure($config->api_url, $config->public_key, $config->shared_secret);
            }
        }
    }

?>
