<?php

namespace Goma\Logging\Test;

use Goma\Logging\Logger;

defined("IN_GOMA") or die();

/**
 * Tests Logger-Class.
 *
 * @package	goma/logging
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */
class LoggerTest extends \GomaUnitTest
{
    /**
     * tests if folder exists.
     */
    public function testFolderExists() {
        $this->assertTrue(is_dir(ROOT . LOG_FOLDER));
    }

    /**
     * Tests if logging creates file. Log-Level = LOG_LEVEL_LOG
     */
    public function testLog() {
        $expectedFile = ROOT . LOG_FOLDER . "/log/" . date("m-d-y") . "/1.log";
        if(is_file($expectedFile)) {
            unlink(is_file($expectedFile));
        }

        Logger::log("Test");

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if error-logging creates file. . Log-Level = LOG_LEVEL_ERROR
     */
    public function testErrorLog() {
        $expectedFile = ROOT . LOG_FOLDER . "/error/" . date("m-d-y") . "/1.log";
        if(is_file($expectedFile)) {
            unlink(is_file($expectedFile));
        }

        Logger::log("Test", Logger::LOG_LEVEL_ERROR);

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if slow-query-logging creates file. . Log-Level = LOG_LEVEL_SLOW_QUERY
     */
    public function testSlowQueryLog() {
        $expectedFile = ROOT . LOG_FOLDER . "/slow_queries/" . date("m-d-y") . "/1.log";
        if(is_file($expectedFile)) {
            unlink(is_file($expectedFile));
        }

        Logger::log("Test", Logger::LOG_LEVEL_SLOW_QUERY);

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if profile-logging creates file. . Log-Level = LOG_LEVEL_PROFILE
     */
    public function testProfileLog() {
        $expectedFile = ROOT . LOG_FOLDER . "/profile/" . date("m-d-y") . "/".date("H_i_s")."_1.log";
        if(is_file($expectedFile)) {
            unlink(is_file($expectedFile));
        }

        Logger::log("Test", Logger::LOG_LEVEL_PROFILE);

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if debug-logging creates file. . Log-Level = LOG_LEVEL_DEBUG
     */
    public function testDebugLog() {
        $expectedFile = ROOT . LOG_FOLDER . "/debug/" . date("m-d-y") . "/".date("H_i_s")."_1.log";
        if(is_file($expectedFile)) {
            unlink(is_file($expectedFile));
        }

        Logger::log("Test", Logger::LOG_LEVEL_DEBUG);

        $this->assertTrue(is_file($expectedFile));
    }
}
