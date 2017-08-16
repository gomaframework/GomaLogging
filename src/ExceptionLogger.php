<?php

namespace Goma\Logging;

use Goma\ENV\GomaENV;
use Throwable;

defined("IN_GOMA") or die();

/**
 * Extension for ExceptionManager to enable Logging for that.
 *
 * @package	goma/logging
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */
class ExceptionLogger
{
    /**
     * Logger for Exceptions.
     * @param Throwable $exception
     */
    public static function logException(Throwable $exception) {
        if(isset($exception->isIgnorable) && $exception->isIgnorable) {
            return;
        }

        $uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : (isset($_SERVER["argv"]) ? implode(" ", $_SERVER["argv"]) : null);

        $message = get_class($exception) . " " . $exception->getCode() . ":\n\n" . $exception->getMessage() . "\n".
            self::exception_get_dev_message($exception)." in ".
            $exception->getFile() . " on line ".$exception->getLine().".\n\n Backtrace: " . $exception->getTraceAsString();
        $currentPreviousException = $exception;
        while($currentPreviousException = $currentPreviousException->getPrevious()) {
            $message .= "\nPrevious: " . get_class($currentPreviousException) . " " . $currentPreviousException->getMessage() . "\n" . self::exception_get_dev_message($currentPreviousException)."\n in "
                . $currentPreviousException->getFile() . " on line ".$currentPreviousException->getLine() . ".\n" . $currentPreviousException->getTraceAsString();
        }
        Logger::log($message, Logger::LOG_LEVEL_ERROR);

        $debugMsg = "URL: " . $uri . "\nComposer: " . print_r(GomaENV::getProjectLevelComposerArray(), true) .
            " Installed: ".print_r(GomaENV::getProjectLevelInstalledComposerArray(), true)."\n\n" . $message;
        Logger::log($debugMsg, Logger::LOG_LEVEL_DEBUG);
    }


    /**
     * Gets developer message from exception when method exists.
     *
     * @param string $exception
     * @return string
     */
    public static function exception_get_dev_message($exception) {
        if(method_exists($exception, "getDeveloperMessage")) {
            return "\n\t\t" . str_replace("\n", "\n\t\t", $exception->getDeveloperMessage()) . "\n";
        }

        return "";
    }
}
