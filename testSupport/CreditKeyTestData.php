<?php
    namespace CreditKey\TestSupport;

    final class CreditKeyTestData
    {
        private static $pathToSource;

        public static function getApiConfiguration()
        {
            $rake_output = self::executeRakeTask('ck:test_support:sdk_authentication');
            return json_decode($rake_output);
        }

        public static function completedApplication()
        {
            $rake_output = self::executeRakeTask('ck:test_support:create_completed_application');
            $result = json_decode($rake_output);
            return $result->id;
        }

        public static function newOrder()
        {
            $rake_output = self::executeRakeTask('ck:test_support:create_new_order');
            $result = json_decode($rake_output);
            return $result->id;
        }

        public static function confirmedOrder()
        {
            $rake_output = self::executeRakeTask('ck:test_support:create_confirmed_order');
            $result = json_decode($rake_output);
            return $result->id;
        }

        public static function setSourcePath($pathToSource)
        {
            self::$pathToSource = $pathToSource;
        }

        public static function executeRakeTask($task)
        {
            return shell_exec('cd ' . self::getPathToSource() . ' && bundle exec rake ' . $task . ' 2>/dev/null');
        }

        private static function getPathToSource()
        {
            if (!isset(self::$pathToSource))
            {
                if (getenv('CREDITKEY_SOURCE_PATH') == false)
                {
                    throw new Exception('Credit Key Source Path Missing');
                }

                self::$pathToSource = getenv('CREDITKEY_SOURCE_PATH');
            }
            return self::$pathToSource;
        }

        public static function cartContents()
        {
            return array(
                new \CreditKey\Models\CartItem('100010', 'Fun Squeegee', 15.99, '1020329892', 3, null, 'Orange'),
                new \CreditKey\Models\CartItem('102302', 'Jordache Mens Jean', 89.99, '1928392983', 1, '38', 'Black'),
                new \CreditKey\Models\CartItem('192832', 'Apple IIe Computer', 1923.82, '1029382923', 1, null, null)
            );
        }

        public static function billingAddress()
        {
            return new \CreditKey\Models\Address('Joe', 'Buyer', 'joebuyer@creditkey.com',
                '145 S Fairfax Ave', 'Suite 200', 'Los Angeles', 'CA', '90036', '213-555-1212');
        }

        public static function shippingAddress()
        {
            return new \CreditKey\Models\Address('Joe', 'Receiver', 'joerecevier@creditkey.com',
                '15 Shatzell Ave', null, 'Rhinecliff', 'NY', '12574', '212-555-1212');
        }

        public static function charges()
        {
            return new \CreditKey\Models\Charges(2061.78, 49.00, 105.53, 10.00, 2206.31);
        }
    }
?>
