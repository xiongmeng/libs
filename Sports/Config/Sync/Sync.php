<?php
namespace Sports\Config\Sync;

use Sports\Config\Storage\StorageAbstract;

interface Sync
{
    /**
     * @abstract
     * @param StorageAbstract $oSource
     * @param StorageAbstract $oDestination
     * @return mixed
     */
    public function sync($oSource, $oDestination);
}
