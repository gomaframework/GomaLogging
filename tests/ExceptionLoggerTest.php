<?php

namespace Goma\Logging\Test;

use Goma\ENV\GomaENV;
use Goma\Logging\ExceptionLogger;

defined("IN_GOMA") or die();

/**
 * Tests ExceptionLogger-Class.
 *
 * @package    goma/logging
 * @link    http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author    Goma-Team
 */
class ExceptionLoggerTest extends \GomaUnitTest
{
    /**
     * Tests if logging of an not developer presentable exception is not writing a file.
     */
    public function testIsNotDeveloperPresentable()
    {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/debug/" . date("m-d-y") . "/" . date("H_i_s") . "_1.log";
        if (is_file($expectedFile)) {
            unlink($expectedFile);
        }

        $exception = new \Exception();
        $exception->isDeveloperPresentable = false;

        ExceptionLogger::logException($exception);
        $this->assertFalse(is_file($expectedFile));
    }

    /**
     * Tests logging of an exception is writing a file with exception message.
     */
    public function testLogsException()
    {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/debug/" . date("m-d-y") . "/" . date("H_i_s") . "_1.log";
        if (is_file($expectedFile)) {
            unlink($expectedFile);
        }

        $exception = new \Exception("My Exception");

        ExceptionLogger::logException($exception);
        $this->assertTrue(is_file($expectedFile));
        $this->assertRegExp("/My Exception/", file_get_contents($expectedFile));
    }
}
