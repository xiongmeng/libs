<?php
namespace Sports\Booking;
use Sports\Config\ConfigSingle;
use Sports\Constant\Finance;
use Sports\Exception\ItemNotExistException;
use Sports\Exception\LogicException;
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
     * @return null|OrderService
     */
    public function getOrderService()
    {
        if($this->oOrderService == null){
            $this->oOrderService = new OrderService($this->getDbAdapter());
        }

        return $this->oOrderService;
    }

    /**
     * @param $iOrderId
     * @return array
     * @throws \Sports\Exception\LogicException
     * @throws \Sports\Exception\ItemNotExistException
     */
    public function refreshBooking($iOrderId)
    {
        $sFilter = ConfigSingle::get('points_entry_court_filters');
        $aFilter = json_decode($sFilter, true);

        if(!isset($aFilter['ids'])){
            return sprintf('unSupport because ids is empty(%s)', $sFilter);
        }

        $oOrderVo = $this->getOrderService()->getById($iOrderId);
        if(empty($oOrderVo)){
            throw new ItemNotExistException(sprintf('order(%s) is not exist', $iOrderId));
        }

        if(!in_array($oOrderVo->getCourId(), $aFilter['ids'])){
            return sprintf('unSupport because %s not in ids %s', $iOrderId, $sFilter);
        }

        $oSdkPointService = $this->getPointService();
        $aAction = $oSdkPointService->checkIsEarned(
            $oOrderVo->getUserId(), Finance::RELATION_BOOKING, $iOrderId);
        if(!empty($aAction)){
            throw new LogicException(
                sprintf('duplicate order %s, action: %s', $iOrderId, json_encode($aAction)));
        }

        return $oSdkPointService->earn($oOrderVo->getUserId(),
            $oOrderVo->getCost(), Finance::RELATION_BOOKING, $iOrderId);
    }

    /**
     * @param $iOrderId
     * @return array
     * @throws \Sports\Exception\ItemNotExistException
     */
    public function refreshCancelBooking($iOrderId)
    {
        $oOrderVo = $this->getOrderService()->getById($iOrderId);
        if(empty($oOrderVo)){
            throw new ItemNotExistException(sprintf('order(%s) is not exist', $iOrderId));
        }

        $oSdkPointService = $this->getPointService();
        $aAction = $oSdkPointService->checkIsEarned(
            $oOrderVo->getUserId(), Finance::RELATION_BOOKING, $iOrderId);
        if(empty($aAction)){
            return sprintf('order(%s) is not earn points', $iOrderId);
        }

        return $oSdkPointService->cost(
            $oOrderVo->getUserId(),$oOrderVo->getCost(), Finance::RELATION_CANCEL_BOOKING, $iOrderId);
    }
}
