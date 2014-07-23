<?php
namespace Sports\Cache;

interface CacheInterface
{
    /**
     * 缓存指定key的指定信息
     * @param string $sKey
     * @param mixed $mValue
     * @param int $sExpire
     */
    public function set($sKey, $mValue, $iExpire = null);

    /**
     * 获取指定key信息
     * @param $sKey
     */
    public function get($sKey);
}
