<?php
spl_autoload_register(function($sClass){
    $file = __DIR__.'/'.strtr($sClass, '\\', '/').'.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

