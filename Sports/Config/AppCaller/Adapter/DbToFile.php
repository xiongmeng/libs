<?php
/**
 * Description
 */

namespace Sports\Config\AppCaller\Adapter;


use Sports\Config\Storage\StorageDatabase;
use Sports\Config\Storage\StorageFileKeyValue;
use Sports\Config\Storage\StorageMemory;
use Sports\Config\Sync\ReplaceSync;
use Sports\Config\VO\ConfigVO;
use Sports\Config\VO\UniqueVO;

class DbToFile extends AdapterAbstract
{
    private static $instance = null;

    private $DBStorage = null;
    private $fileStorage = null;
    private $memoryStorage = null;
    private $blackKeyList = array();

    private $syncIntval;
    private $appName;

    public static function getInstance($storageConfig)
    {
        if (self::$instance == null) {
            self::$instance = new self($storageConfig);
        }

        return self::$instance;
    }

    private function __construct($storageConfigArray)
    {
        if (!isset($storageConfigArray['db'])) {
            throw new \Exception("configSingle config data error! db is not exists!");
        }

        if (!isset($storageConfigArray['file'])) {
            throw new \Exception('configSingle config data error! file is not exists!');
        }

        if (!isset($storageConfigArray['syncInterval'])) {
            throw new \Exception('configSingle config data error! syncInterval is not exists!');
        }

        if (!isset($storageConfigArray['appName'])) {
            throw new \Exception('configSingle config data error! appName is not exists!');
        }

        $this->DBStorage = new StorageDatabase($storageConfigArray['db']);
        $this->fileStorage = new StorageFileKeyValue($storageConfigArray['file']);

        $this->memoryStorage = new StorageMemory(
            isset($storageConfigArray['memory']) ? $storageConfigArray['memory'] : array());

        $this->syncIntval = $storageConfigArray['syncInterval'];
        $this->appName = $storageConfigArray['appName'];

        $this->ensureSyncedPeriodic();
    }

    /**
     * 获取指定key的配置
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        if (in_array($key, $this->blackKeyList)){
            throw new \Exception("$key is blacklist not exist!");
        }

        $oQueryVO = new UniqueVO($key, $this->appName, ConfigVO::EXT_DEFAULT);

        $oConfigVO = $this->queryKeyWithHierarchy($this->memoryStorage, $oQueryVO);

        if($oConfigVO === null){
            $oConfigVO = $this->queryKeyWithHierarchy($this->DBStorage, $oQueryVO);

            if($oConfigVO === null){
                array_push($this->blackKeyList, $key);
                throw new \Exception("$key not exist!");
            }
        }

        return $oConfigVO->getValue();
    }

    /**
     * 保证周期性的同步
     */
    private function ensureSyncedPeriodic()
    {
        if($this->whetherSyncNeeded()){
            $sync = new ReplaceSync();
            $sync->sync($this->DBStorage, $this->memoryStorage);
            $sync->sync($this->memoryStorage, $this->fileStorage);

            $this->blackKeyList = array();
        }else{
            $sync = new ReplaceSync();
            $sync->sync($this->fileStorage, $this->memoryStorage);
        }
    }

    /**
     * 获取上次同步时间
     * @return int
     */
    private function whetherSyncNeeded()
    {
        $sFilePath = $this->fileStorage->getPath();
        return (!file_exists($sFilePath)) ||
            ((time() - filemtime($sFilePath)) >= $this->syncIntval) || (filesize($sFilePath) <= 0);
    }

    private function __clone()
    {
        // disabled clone methon
    }
}