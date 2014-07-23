<?php
namespace Sports\Log\LogWBY;

class Log4php extends LogAbstract
{
    /**
     * 是否每次 log 输出的时候 都需要重新配置 configure文件 ，以防与其他调用者冲突
     * 如果为true，则每次调用log的时候都会调用Logger::configure($mConfig)，这样比较浪费资源
     * @var bool
     */
    private $bConfigEveryTime = false;
    /**
     * 当输出日志的时候指定的logger是否必须存在，即配置文件中必须指明指定的logger。
     * 如果为false，当指定的logger未配置时，则会从rootLogger继承而来。
     * @var bool
     */
    private $bLoggerMustExist = false;

    private $mConfig = '';
    /**
     * construct function
     * @param $aConfig -
     *              array(
     *                   'lib_path'=>'the log4php's lib path',
     *                  'config'=>'the log4php's config path'
     *                  'config_every_time'=>0 or 1
     *                  'logger_must_exist'=>0 or 1
     *              )
     * @throws \Exception - if class 'Logger' is not exist ,then throw exception
     */
    public function __construct($aConfig){
        parent::__construct($aConfig);

        //获取指定的log4php所在的路径
        if(isset($aConfig['lib_path']) && !class_exists('Logger'))
            require_once(realpath($aConfig['lib_path'].'/Logger.php'));

        if(!class_exists('Logger'))
            throw new \Exception('the lib of log4php is not exist!');

        //获取配置文件
        $this->mConfig = __DIR__.'/log4php.xml';
        isset($aConfig['config']) && $this->mConfig = $aConfig['config'];

        //如果设置了配置文件，则从配置文件中读取
        if(!empty($this->mConfig)){
            //防止umask设置对创建的文件夹权限有影响
            $iOldMask = umask(0);
            \Logger::configure($this->mConfig);
            umask($iOldMask);
        }

        //加载开关配置
        isset($aConfig['config_every_time']) && $this->bConfigEveryTime = ($aConfig['config_every_time']==1);
        isset($aConfig['logger_must_exist']) && $this->bLoggerMustExist = ($aConfig['logger_must_exist']==1);
    }

    /**
     * @var \Logger
     */
    private $oCurLogger = null;

    /**
     * 获取指定module的logger
     * @param $sModule - module's name
     * @return Log4php|LogAbstract
     * @throws \Exception - if specified module is not exist in config file ,then throw exception
     */
    public function setLogger($eModule){
        //这里为了防止与其他地方configure冲突，如果每次都configure一遍的，则比较浪费
        if($this->bConfigEveryTime && !empty($this->mConfig))
            \Logger::configure($this->mConfig);

        //检测指定的module是否在配置文件已经配置，如果开关打开的话
        if($this->bLoggerMustExist && !\Logger::exists($eModule))
            throw new \Exception(sprintf('the specified module:%s must exist in the config file',$eModule));

        //如果为空，则返回rootLogger
        if(empty($eModule))
            $this->oCurLogger = \Logger::getRootLogger();
        else{
            $this->oCurLogger = \Logger::getLogger($eModule);
        }
        return $this;
    }

    /**
     * 记录日志
     * @param $sMsg     - 要记录的msg
     * @param $sTag     - 用以区分用tag
     * @param $eLevel   - 日志级别
     * @return mixed    - null
     */
    public function log($sMsg,$sTag,$eLevel){
        if($this->oCurLogger == null)
            return ;
        $oLogger = $this->oCurLogger;

        //加入Tag信息
        $sTagInfo = is_string($sTag) && strlen($sTag)>0 ? sprintf('[%s] ',$sTag) : '';
        $sMsg = $sTagInfo.$sMsg;

        //分别调用log4php的日志信息
        switch($eLevel){
            case self::LEVEL_DEBUG:
                $oLogger->debug($sMsg);
                break;
            case self::LEVEL_INFO:
                $oLogger->info($sMsg);
                break;
            case self::LEVEL_WARN:
                $oLogger->warn($sMsg);
                break;
            case self::LEVEL_ERROR:
                $oLogger->error($sMsg);
                break;
            case self::LEVEL_FATAL:
                $oLogger->fatal($sMsg);
                break;
            default:
                break;
        }
    }
}
