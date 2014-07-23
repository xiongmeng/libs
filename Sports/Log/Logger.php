<?php
class Sports\_Log_Logger
{
    /***
     * @static
     * @param $loggerName string the logger name
     * @return Logger the logger instance.
     */
    public static function getLogger($loggerName)
    {
        require_once('log4php/src/main/php/Logger.php');
        Logger::configure(APPLICATION_PATH . '/configs/log4php.xml');
        return Logger::getLogger($loggerName);
    }
}
