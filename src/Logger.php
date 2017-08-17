<?php

namespace Goma\Logging;

defined("IN_GOMA") or die();

/**
 * Logging provides log-functions.
 *
 * @package	goma/logging
 * @link 	http://goma-cms.org
 * @license LGPL http://www.gnu.org/copyleft/lesser.html see 'license.txt'
 * @author 	Goma-Team
 */
class Logger
{
    const LOG_LEVEL_LOG = 1;
    const LOG_LEVEL_ERROR = 2;
    const LOG_LEVEL_DEBUG = 4;
    const LOG_LEVEL_SLOW_QUERY = 8;
    const LOG_LEVEL_PROFILE = 16;

    /**
     * log-file cache. Goma uses per session only one file.
     *
     * @var array
     */
    protected static $logCache = array();

    /**
     * @var int
     */
    private static $fileSizeLimit = 100000;

    /**
     * @param string $info
     * @param int $level
     */
    public static function log($info, $level = self::LOG_LEVEL_LOG) {
        if(!is_string($info)) {
            throw new \InvalidArgumentException("\$info must be string for Logging::log");
        }

        $logged = false;
        if($level & self::LOG_LEVEL_LOG) {
            self::putLogFile(self::getLogFile("log"), $info);
            $logged = true;
        }

        if($level & self::LOG_LEVEL_ERROR) {
            self::putLogFile(self::getLogFile("error"), $info);
            $logged = true;
        }

        if($level & self::LOG_LEVEL_DEBUG) {
            self::putLogFile(self::getLogFile("debug", true), $info);
            $logged = true;
        }

        if($level & self::LOG_LEVEL_PROFILE) {
            self::putLogFile(self::getLogFile("profile"), $info);
            $logged = true;
        }

        if($level & self::LOG_LEVEL_SLOW_QUERY) {
            self::putLogFile(self::getLogFile("slow_queries", true), $info);
            $logged = true;
        }

        if(!$logged) {
            throw new \InvalidArgumentException("Logging::log: Wrong level given.");
        }
    }

    /**
     * @param string $info
     */
    public static function logError($info) {
        self::log($info, self::LOG_LEVEL_ERROR);
    }

    /**
     * Writes log-entry to file and prepends date in front.
     *
     * @param string $logFile
     * @param string $info
     */
    protected static function putLogFile($logFile, $info) {
        touch($logFile);

        if(!@file_put_contents($logFile, date("Y-m-d H:i:s") . ': ' . $info . "\n\n", null) ||
            !@chmod($logFile, 0777)) {
            throw new \LogicException("LOG_FOLDER should be writable. Could not write log-entry.");
        }
    }

    /**
     * Gets latest log-file based on size of previous files.
     *
     * @param string $folder
     * @param bool $noAppend whether to always create a new file.
     * @return mixed
     */
    protected static function getLogFile($folder, $noAppend = false) {
        if(!isset(self::$logCache[$folder])) {
            $logFolder = ROOT . GOMA_DATADIR . "/" . LOG_FOLDER . "/" . $folder . "/" . date("m-d-y") . "/";

            if (!is_dir($logFolder)) {
                if (!mkdir($logFolder, 0777, true)) {
                    throw new \LogicException("LOG_FOLDER should be writable.");
                }
            }

            $noAppendFileName = $noAppend ? date("H_i_s") . "_" : "";
            $file = $folder . $noAppendFileName . "1.log";
            $i = 1;
            while (file_exists($folder . $i . ".log") && ($noAppend || filesize($file) > static::$fileSizeLimit)) {
                $i++;
                $file = $folder . $noAppendFileName . "_" . $i . ".log";
            }

            self::$logCache[$folder] = $file;
        }

        return self::$logCache[$folder];
    }
}
