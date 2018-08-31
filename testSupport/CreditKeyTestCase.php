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
            $config = CreditKeyTestData::getApiConfiguration();
            \CreditKey\Api::configure(\CreditKey\TestSupport\CreditKeyTestData::API_ENDPOINT,
                $config->public_key, $config->shared_secret);
        }
    }
?>
