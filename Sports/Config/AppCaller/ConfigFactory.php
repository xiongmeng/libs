<?php
/**
 * Description
 */

namespace Sports\Config\AppCaller;


class ConfigFactory
{
    /**
     * @param $adapterName
     * @param $configData
     * @return $object
     * @throws \Exception
     */
    public static function factory($adapterName, $configData)
    {
        $adapterClass = "Sports\Config\\AppCaller\\Adapter\\" . $adapterName;
        if (class_exists("$adapterClass")) {
            if (in_array("Sports\Config\\AppCaller\\Adapter\\AdapterAbstract", class_parents($adapterClass))) {
                return $adapterClass::getInstance($configData);
            } else {
                throw new \Exception("$adapterClass must be inheritsed Sports\Config\\AppCaller\\Adapter\\AdapterAbstract");
            }
        } else {
            throw new \Exception("$adapterClass is not exists!");
        }
    }
}