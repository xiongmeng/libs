<?php
namespace Sports\Log;
use Sports\Log\LogWBY\LogAbstract;
use Sports\Log\LogWBY\Log4php;
/**
 * LogWBY::debug()
 * LogWBY::info()
 * LogWBY::warn()
 * LogWBY::error()
 * LogWBY::fatal()
 * please ensure LogWBY::init() has been called when you call the above methods.
 *
 * if the config_file is not supported ,then the ./Log/log4php.xml is used.
 *
 */
class LogWBY
{
    /**
     * @var LogWBY
     */
    private static $self = null;

    /**
     * @static
     * @return LogWBY
     */
    private static function instance()
    {
        self::$self == null && self::$self = new LogWBY();
        return self::$self;
    }


    /**
     * @var LogAbstract
     */
    private $oLogger = null;

    /**
     * 初始化日志
     * @static
     * @param $sLib - 'log4php'
     * @param $aConfig -
     *            array(
     *              'lib'=>'log4php',
     *              'log4php'=>array(
     *                   'lib_path'=>'the log4php's lib path',
     *                   'config'=>'the log4php's config path'
     *              )
     *          );
     * @throws \Exception if $slib is not support , then throw exception
     */
    public static function init($sLib, $aConfig)
    {
        $oSelf = self::instance();
        if (strtolower($sLib) == 'log4php') {
                $oSelf->oLogger = new Log4php($aConfig);
        } else {
            throw new \Exception(sprintf('the specified lib:"%s" is not supported !',$sLib));
        }
    }

    /**
     * 调试日志
     * @static
     * @param $sMsg
     * @param $sModule - if unsupported,then use default logger(for log4php,the root logger)
     * @param $sTag - unused now
     */
    public static function debug($sMsg, $sModule='', $sTag = '')
    {
        $oSelf = self::instance();
        $oSelf->oLogger && $oSelf->oLogger->setLogger($sModule)->log($sMsg, $sTag, LogAbstract::LEVEL_DEBUG);
    }

    /**
     * 输出警告日志
     * @static
     * @param $sMsg
     * @param $sModule - if unsupported,then use default logger(for log4php,the root logger)
     * @param $sTag - unused now
     */
    public static function warn($sMsg, $sModule='', $sTag = '')
    {
        $oSelf = self::instance();
        $oSelf->oLogger && $oSelf->oLogger->setLogger($sModule)->log($sMsg, $sTag, LogAbstract::LEVEL_WARN);
    }

    /**
     * 输出info级别的信息
     * @static
     * @param $sMsg
     * @param $sModule - if unsupported,then use default logger(for log4php,the root logger)
     * @param $sTag - unused now
     */
    public static function info($sMsg, $sModule='', $sTag = '')
    {
        $oSelf = self::instance();
        $oSelf->oLogger && $oSelf->oLogger->setLogger($sModule)->log($sMsg, $sTag, LogAbstract::LEVEL_INFO);
    }

    /**
     * 输出错误日志
     * @static
     * @param $sMsg
     * @param $sModule - if unsupported,then use default logger(for log4php,the root logger)
     * @param $sTag - unused now
     */
    public static function error($sMsg, $sModule='', $sTag = '')
    {
        $oSelf = self::instance();
        $oSelf->oLogger && $oSelf->oLogger->setLogger($sModule)->log($sMsg, $sTag, LogAbstract::LEVEL_ERROR);
    }

    /**
     * 输出严重级别错误的日志
     * @static
     * @param $sMsg
     * @param $sModule - if unsupported,then use default logger(for log4php,the root logger)
     * @param $sTag - unused now
     */
    public static function fatal($sMsg, $sModule='', $sTag = '')
    {
        $oSelf = self::instance();
        $oSelf->oLogger && $oSelf->oLogger->setLogger($sModule)->log($sMsg, $sTag, LogAbstract::LEVEL_FATAL);
    }
}
