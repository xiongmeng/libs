<?php
namespace Sports\Utility;

class DBHelper
{
    private static $oMasterDbAdapter = null;

    /**
     * @return null|\Zend\Db\Adapter\Adapter
     */
    public static function masterAdapterFromConfigSingle()
    {
        if(self::$oMasterDbAdapter === null){
            $aDsn = \Sports\Config\ConfigSingle::get('db_gotennis_dsn');
            self::$oMasterDbAdapter = new \Zend\Db\Adapter\Adapter(json_decode($aDsn, true));
        }
        return self::$oMasterDbAdapter;
    }
}