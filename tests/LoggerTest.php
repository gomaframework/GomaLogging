<?php

namespace Goma\Logging\Test;

use Goma\ENV\GomaENV;
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
        $this->assertTrue(is_dir(GomaENV::getDataDirectory() . LOG_FOLDER));
    }

    /**
     * Tests if logging creates file. Log-Level = LOG_LEVEL_LOG
     */
    public function testLog() {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/log/" . date("m-d-y") . "/1.log";
        if(is_file($expectedFile)) {
            unlink($expectedFile);
        }

        Logger::log("Test");

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if error-logging creates file. . Log-Level = LOG_LEVEL_ERROR
     */
    public function testErrorLog() {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/error/" . date("m-d-y") . "/1.log";
        if(is_file($expectedFile)) {
            unlink($expectedFile);
        }

        Logger::log("Test", Logger::LOG_LEVEL_ERROR);

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if slow-query-logging creates file. . Log-Level = LOG_LEVEL_SLOW_QUERY
     */
    public function testSlowQueryLog() {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/slow_queries/" . date("m-d-y") . "/1.log";
        if(is_file($expectedFile)) {
            unlink($expectedFile);
        }

        Logger::log("Test", Logger::LOG_LEVEL_SLOW_QUERY);

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if profile-logging creates file. . Log-Level = LOG_LEVEL_PROFILE
     */
    public function testProfileLog() {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/profile/" . date("m-d-y") . "/".date("H_i_s")."_1.log";
        if(is_file($expectedFile)) {
            unlink($expectedFile);
        }

        Logger::log("Test", Logger::LOG_LEVEL_PROFILE);

        $this->assertTrue(is_file($expectedFile));
    }

    /**
     * Tests if profile-logging creates 2 files for two logs. . Log-Level = LOG_LEVEL_PROFILE
     */
    public function test2ProfileLog() {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/profile/" . date("m-d-y") . "/".date("H_i_s")."_1.log";
        if(is_file($expectedFile)) {
            unlink($expectedFile);
        }

        $expectedFile2 = GomaENV::getDataDirectory() . LOG_FOLDER . "/profile/" . date("m-d-y") . "/".date("H_i_s")."_2.log";
        if(is_file($expectedFile2)) {
            unlink($expectedFile2);
        }

        Logger::log("Test", Logger::LOG_LEVEL_PROFILE);
        Logger::log("Test2", Logger::LOG_LEVEL_PROFILE);

        $this->assertTrue(is_file($expectedFile));
        $this->assertTrue(is_file($expectedFile2));
    }

    /**
     * Tests if debug-logging creates file. . Log-Level = LOG_LEVEL_DEBUG
     */
    public function testDebugLog() {
        $expectedFile = GomaENV::getDataDirectory() . LOG_FOLDER . "/debug/" . date("m-d-y") . "/".date("H_i_s")."_1.log";
        if(is_file($expectedFile)) {
            unlink($expectedFile);
        }

        Logger::log("Test", Logger::LOG_LEVEL_DEBUG);

        $this->assertTrue(is_file($expectedFile));
    }
}
