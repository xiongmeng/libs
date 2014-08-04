<?php
namespace Sports\Utility;

class ArrayHelper
{
    public static function copyIfExist(array $aSrcArray, array $aKeys)
    {
        $aResult = array();
        return self::mergeIfExist($aSrcArray, $aKeys, $aResult);
    }

    public static function mergeIfExist(array $aSrcArray, array $aKeys, array &$aDesArray)
    {
        foreach($aKeys as $sKey){
            array_key_exists($sKey, $aSrcArray) && $aDesArray[$sKey] = $aSrcArray[$sKey];
        }
        return $aDesArray;
    }
}
