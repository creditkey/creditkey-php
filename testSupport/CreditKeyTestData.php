<?php
    namespace CreditKey\TestSupport;

    final class CreditKeyTestData
    {
        private static $pathToSource;

        public static function getApiConfiguration()
        {
            $rake_output = CreditKeyTestData::executeRakeTask('ck:test_support:sdk_authentication');
            return json_decode($rake_output);
        }

        public static function createCompletedApplication()
        {
            $rake_output = CreditKeyTestData::executeRakeTask('ck:test_support:create_completed_application');
            $result = json_decode($rake_output);
            return $result->id;
        }

        public static function setSourcePath($pathToSource)
        {
            self::$pathToSource = $pathToSource;
        }

        public static function executeRakeTask($task)
        {
            return shell_exec('cd ' . self::getPathToSource() . ' && rake ' . $task);
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
    }
?>
