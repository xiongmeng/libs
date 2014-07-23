<?php
namespace Sports\Config\Storage;

use Sports\Config\VO\ConfigVO;

interface Put
{
    /**
     * @abstract
     * @param mixed $mConfigVO  a Config or a Config Array
     * @return mixed
     */
    public function put($mConfigVO);
}
