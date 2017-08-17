<?php

namespace Goma\Logging;

use Goma\Error\ExceptionHandler;

defined("IN_GOMA") OR die();

/**
 * Used for compatibility with GomaErrorHandling.
 *
 * @package    goma/Logging
 * @link    http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author    Goma-Team
 */
if (!interface_exists(ExceptionHandler::class)) {
    interface LoggingExceptionHandler
    {
    }
} else {
    interface LoggingExceptionHandler extends ExceptionHandler
    {
    }
}
