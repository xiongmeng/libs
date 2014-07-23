<?php
namespace Sports\Cache\Adapter;

use Sports\Cache\CacheInterface;

class CacheMemory implements CacheInterface
{
    const LIMIT_UPPER_BOUND = 1024;
    const LIMIT_LOWER_BOUND = 3;

    private $iLimit = 20;
    public function __construct($iLimit = 20)
    {
        if(!is_numeric($iLimit) || ($iLimit < self::LIMIT_LOWER_BOUND) || ($iLimit > self::LIMIT_UPPER_BOUND)){
            throw new \Exception(sprintf(
                'please ensure %d between %d and %d',$iLimit,self::LIMIT_LOWER_BOUND,self::LIMIT_UPPER_BOUND));
        }

        $this->iLimit = $iLimit;
        $this->aCacheArray = array();
    }

    private $aCacheArray = null;
    /**
     * @param string $sKey
     * @param mixed $mValue
     * @param int $sExpire
     */
    public function set($sKey, $mValue, $iExpire = null)
    {
        //如果过期，则删除此项
        if(is_numeric($iExpire) && $iExpire < time()){
            unset($this->aCacheArray[$sKey]);
            return ;
        }

        $this->aCacheArray[$sKey] = $mValue;

        while(count($this->aCacheArray) > $this->iLimit){
            array_shift($this->aCacheArray);
        }
    }

    /**
     * @param $sKey
     */
    public function get($sKey)
    {
        if(isset($this->aCacheArray[$sKey])){
            return $this->aCacheArray[$sKey];
        }
        return null;
    }

    /**
     * @return array|null
     */
    public function getAll()
    {
        return $this->aCacheArray;
    }
}