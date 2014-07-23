<?php
namespace Sports\Config\Storage;

use Sports\Config\VO\QueryVO;
use Sports\Config\VO\ConfigVO;

interface Query
{
    /**
     * @abstract
     * @param QueryVO $oQueryVO
     * @return ConfigVO[]|void
     */
    public function query($oQueryVO = null);
}
