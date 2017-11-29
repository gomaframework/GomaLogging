<?php

namespace Goma\Logging;

use Goma\ENV\GomaENV;
use Goma\Error\ExceptionHandler;
use Throwable;

defined("IN_GOMA") or die();

/**
 * Extension for ExceptionManager to enable Logging for that.
 *
 * @package    goma/logging
 * @link    http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author    Goma-Team
 */
class ExceptionLogger implements LoggingExceptionHandler
{
    /**
     * Wrapper for isDeveloperPresentable.
     * Using ExceptionHandler is existing, else it checks for property $isDeveloperPresentable or returns true.
     *
     * @param Throwable $exception
     * @return bool
     */
    protected static function isDeveloperPresentable($exception)
    {
        if(class_exists(ExceptionHandler::class)) {
            return ExceptionHandler::isDeveloperPresentableException($exception);
        }

        return isset($exception->isDeveloperPresentable) ? $exception->isDeveloperPresentable : true;
    }

    /**
     * At this point exceptions can be handled.
     * Return true if exception was handled and default handling or handling by others should be stopped.
     *
     * @param Throwable $exception
     * @return bool|null
     */
    public static function handleException($exception)
    {
        $message = get_class($exception) . " " . $exception->getCode() . ":\n\n" . $exception->getMessage() . "\n" .
            self::exception_get_dev_message($exception) . " in " .
            $exception->getFile() . " on line " . $exception->getLine() . ".\n\n Backtrace: " . $exception->getTraceAsString();

        $currentPreviousException = $exception;
        while ($currentPreviousException = $currentPreviousException->getPrevious()) {
            $message .= "\nPrevious: " . get_class($currentPreviousException) . " " . $currentPreviousException->getMessage() . "\n" . self::exception_get_dev_message($currentPreviousException) . "\n in "
                . $currentPreviousException->getFile() . " on line " . $currentPreviousException->getLine() . ".\n" . $currentPreviousException->getTraceAsString();
        }

        if (self::isDeveloperPresentable($exception)) {
            $uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : (isset($_SERVER["argv"]) ? implode(" ", $_SERVER["argv"]) : null);
            
            Logger::log($message, Logger::LOG_LEVEL_ERROR);

            $debugMsg = $message . "\n\n\n" . "URL: " . $uri . "\nComposer: " . print_r(GomaENV::getProjectLevelComposerArray(), true) .
                " Installed: " . print_r(GomaENV::getProjectLevelInstalledComposerArray(), true) . "\n\n";
            Logger::log($debugMsg, Logger::LOG_LEVEL_DEBUG);
        } else {
            Logger::log($message, Logger::LOG_LEVEL_LOG);
        }
    }

    /**
     * Gets developer message from exception when method exists.
     *
     * @param string $exception
     * @return string
     */
    public static function exception_get_dev_message($exception)
    {
        if (method_exists($exception, "getDeveloperMessage")) {
            return "\n\t\t" . str_replace("\n", "\n\t\t", $exception->getDeveloperMessage()) . "\n";
        }
        return "";
    }

    /**
     * Ignorable exceptions are exceptions, which are not leading to a crash of the system, default: false
     *
     * Return null if no decision can be made.
     * Return boolean true or false to decide if ignorable or not.
     *
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isIgnorableException($exception)
    {
        return null;
    }

    /**
     * Developer presentable exceptions are exceptions, which will be printed in development mode even if ignorable.
     * Return null if no decision can be made.
     * Return boolean true or false to decide if developer-presentable or not.
     *
     * @param Throwable $exception
     * @return bool|null
     */
    public static function isDeveloperPresentableException($exception)
    {
        return null;
    }

    /**
     * Alias for handleException.
     *
     * @param Throwable $exception
     */
    public static function logException($exception)
    {
        self::handleException($exception);
    }
}
