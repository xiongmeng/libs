<?php
namespace Sports\Config\Sync;

use Sports\Config\Storage\StorageAbstract;
class MergeSync implements Sync
{
    /**
     * @param StorageAbstract $oSource
     * @param StorageAbstract $oDestination
     * @return mixed|void
     */
    public function sync($oSource, $oDestination)
    {
        if(!($oSource instanceof StorageAbstract) || !($oDestination instanceof StorageAbstract)){
            throw new \Exception('$oSource and $oDestination must be an instance of StorageAbstract');
        }
        if($oSource->equal($oDestination)){
            return ;
        }

        $aSrcCfg = $oSource->query();

        $oDestination->put($aSrcCfg);
    }
}
