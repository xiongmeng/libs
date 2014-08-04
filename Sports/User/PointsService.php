<?php
namespace Sports\User;

use Sports\Config\ConfigSingle;
use Sports\Constant\Finance;
use Sports\Exception\ItemNotExistException;
use Sports\Exception\LogicException;
use Sports\Object\User\RechargeVo;

use Sports\Finance\PointsService as FinancePointService;

/**
 * 积分service
 * Class PointsService
 * @package Finance\Service
 */
class PointsService extends BaseService
{
    private $oPointService = null;
    /**
     * @return null|FinancePointService
     */
    public function getPointService()
    {
        if($this->oPointService == null){
            $sRate = ConfigSingle::get('point_rate');
            $aRates = json_decode($sRate, true);

            $this->oPointService = new FinancePointService($this->getDbAdapter(), $aRates);
        }

        return $this->oPointService;
    }

    private $oOrderService = null;

    /**
     * @return null|RechargeService
     */
    public function getRechargeService()
    {
        if($this->oOrderService == null){
            $this->oOrderService = new RechargeService($this->getDbAdapter());
        }

        return $this->oOrderService;
    }

    /**
     * @param $iRechargeId
     * @return array
     * @throws \Sports\Exception\LogicException
     * @throws \Sports\Exception\ItemNotExistException
     */
    public function refreshRecharge($iRechargeId)
    {
        $oRechargeVo = $this->getRechargeService()->getById($iRechargeId);
        if(empty($oRechargeVo)){
            throw new ItemNotExistException(sprintf('order(%s) is not exist', $iRechargeId));
        }

        $sFilter = ConfigSingle::get('point_enter_recharge_filters');
        $aFilters = json_decode($sFilter, true);
        if(!$this->filterDate($aFilters, $oRechargeVo) || !$this->filterAmount($aFilters, $oRechargeVo)){
            return sprintf("recharge(%s) not in earn condition(%s)", json_encode($oRechargeVo->toArraySerializable()), $sFilter);
        }

        $oSdkPointService = $this->getPointService();
        $aAction = $oSdkPointService->checkIsEarned(
            $oRechargeVo->getUserId(), Finance::RELATION_RECHARGE, $iRechargeId);
        if(!empty($aAction)){
            throw new LogicException(
                sprintf('duplicate order %s, action: %s', $iRechargeId, json_encode($aAction)));
        }

        return $oSdkPointService->earn($oRechargeVo->getUserId(),
            $oRechargeVo->getPayMoney(), Finance::RELATION_RECHARGE, $iRechargeId);
    }

    private function filterDate($aFilters, RechargeVo $oRecharge)
    {
        if(!isset($aFilters['date'])){
            return false;
        }

        $iCreateTime = $oRecharge->getCreatetime();
        foreach($aFilters['date'] as $sDate){
            list($iStart, $iEnd) = explode('-', $sDate);
            if($iCreateTime >= $iStart && $iCreateTime <= $iEnd){
                return true;
            }
        }

        return false;
    }

    private function filterAmount($aFilters, RechargeVo $oRecharge)
    {
        if(!isset($aFilters['amount'])){
            return false;
        }

        $iPayMoney = $oRecharge->getPayMoney();
        list($iLowerBound, $iUpperBound) = explode('-', $aFilters['amount']);
        if($iPayMoney >= $iLowerBound && $iPayMoney <= $iUpperBound){
            return true;
        }

        return false;
    }
}
