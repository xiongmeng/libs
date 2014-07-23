<?php
namespace Sports\Config\VO;
class QueryVO extends UniqueVO
{
    private $iId = null;
    public function getId()
    {
        return $this->iId;
    }
    public function setId($iId)
    {
        $this->iId = $iId;
    }
}
