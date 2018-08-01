<?php
    use PHPUnit\Framework\TestCase;
    use CreditKey\Customers;

    final class CustomersTest extends TestCase
    {
        public function testFindCustomerStatus()
        {
            $this->assertEquals(true,
                \CreditKey\Customers::findCustomerStatus(null));
        }
    }
?>
