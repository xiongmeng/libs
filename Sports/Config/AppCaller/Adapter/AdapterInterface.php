<?php
/**
 * Description
 */

namespace Sports\Config\AppCaller\Adapter;


interface AdapterInterface
{
    // Dropped abstract static class functions. Due to an oversight,
    // PHP 5.0.x and 5.1.x allowed abstract static functions in classes.
    // As of PHP 5.2.x, only interfaces can have them.
    public static function getInstance($storageConfig);
}