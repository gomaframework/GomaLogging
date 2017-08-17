Goma Logging
=======

GomaLogging provides logging functionality. 

Methods
--
Logger::log($logString, $level) - Logs information, possible levels:
* Logger::LOG_LEVEL_LOG
* Logger::LOG_LEVEL_ERROR
* Logger::LOG_LEVEL_DEBUG
* Logger::LOG_LEVEL_PROFILE
* Logger::LOG_LEVEL_SLOW_QUERY

Levels can also be used together, for example:
<code>
Logger::log("My Log Message", Logger::LOG_LEVEL_DEBUG | Logger::LOG_LEVEL_LOG)
</code>

Configuration via composer.json
--
* goma_log_folder: define own custom log-folder name. Default: logs
