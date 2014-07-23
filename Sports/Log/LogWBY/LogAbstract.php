<?php
namespace Sports\Log\LogWBY;

abstract class LogAbstract
{
    /**
     * 日志级别 - please not modify
     */
    const LEVEL_DEBUG = 10;
    const LEVEL_INFO = 20;
    const LEVEL_WARN = 30;
    const LEVEL_ERROR = 40;
    const LEVEL_FATAL = 50;

    /**
     * 构造函数
     */
    public function __construct($aConfig){
        if(!is_array($aConfig))
            throw new \Exception('$aConfig must ba a array');
    }


    /**
     * 获取指定module的logger
     * @param $sModule - module's name
     * @return LogAbstract
     */
    abstract public function setLogger($sModule);

    /**
     * 记录日志
     * @param $sMsg     - 要记录的msg
     * @param $sTag     - 用以区分用tag
     * @param $eLevel   - 日志级别
     * @return mixed    - null
     */
    abstract public function log($sMsg,$sTag,$eLevel);
}
