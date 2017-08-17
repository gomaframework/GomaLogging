<?php

use Goma\ENV\GomaENV;
use Goma\Error\ExceptionManager;

defined("IN_GOMA") or die();

/**
 * Sets up logging environment.
 *
 * @package	goma/logging
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */

define("LOG_FOLDER", isset(GomaENV::getProjectLevelComposerArray()["log_folder"]) ? GomaENV::getProjectLevelComposerArray()["log_folder"] : "logs");

if(!is_dir(ROOT . LOG_FOLDER)) {
    mkdir(ROOT . LOG_FOLDER);
}

// write tests
if(file_put_contents(ROOT . LOG_FOLDER . "/write.test", "") !== false) {
    @unlink(ROOT . LOG_FOLDER . "/write.test");
} else {
    throw new Exception("Write-Test failed. Please allow write at /" . GOMA_DATADIR);
}

if(class_exists(ExceptionManager::class)) {
    ExceptionManager::registerExceptionHandler(array(ExceptionLogger::class, "logException"));
}
