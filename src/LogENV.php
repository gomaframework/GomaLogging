<?php

use Goma\ENV\GomaENV;
use Goma\Error\ExceptionManager;
use Goma\Logging\ExceptionLogger;

defined("IN_GOMA") or die();

/**
 * Sets up logging environment.
 *
 * @package	goma/logging
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */

define("LOG_FOLDER", isset(GomaENV::getProjectLevelComposerArray()["goma_log_folder"]) ? GomaENV::getProjectLevelComposerArray()["goma_log_folder"] : "logs");

if(!is_dir(GomaENV::getDataDirectory() . LOG_FOLDER)) {
    mkdir(GomaENV::getDataDirectory() . LOG_FOLDER);
}

// write tests
if(file_put_contents(GomaENV::getDataDirectory() . LOG_FOLDER . "/write.test", "") !== false) {
    @unlink(GomaENV::getDataDirectory() . LOG_FOLDER . "/write.test");
} else {
    throw new Exception("Write-Test failed. Please allow write at /" . GOMA_DATADIR);
}

if(!file_exists(GomaENV::getDataDirectory() . LOG_FOLDER . "/.htaccess")) {
    file_put_contents(GomaENV::getDataDirectory() . LOG_FOLDER . "/.htaccess", "deny from all");
}

if(class_exists(ExceptionManager::class)) {
    ExceptionManager::registerExceptionHandler(ExceptionLogger::class);
}
