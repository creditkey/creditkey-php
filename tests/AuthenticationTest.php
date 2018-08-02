<?php
    use CreditKey\TestSupport\CreditKeyTestCase;

    final class AuthenticationTest extends CreditKeyTestCase
    {
        public function testCanAuthenticate()
        {
            $this->assertTrue(\CreditKey\Authentication::authenticate());
        }
    }
?>
