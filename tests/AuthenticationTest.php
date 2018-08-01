<?php
    use PHPUnit\Framework\TestCase;
    use CreditKey\Authentication;

    final class AuthenticationTest extends TestCase
    {
        public function testCanAuthenticate()
        {
            $this->assertEquals(true,
                \CreditKey\Authentication::authenticate(
                    '', '', '',
                    // $_ENV['TEST_CREDITKEY_ENDPOINT'],
                    // $_ENV['TEST_CREDITKEY_PUBLIC_KEY'],
                    // $_ENV['TEST_CREDITKEY_SHARED_SECRET'],
                    false));
        }
    }
?>
