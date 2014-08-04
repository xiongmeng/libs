<?php
namespace Sports\Finance;
use Sports\Finance\Operate\ActionObject;
use Sports\Finance\Operate\ActionService;
use Sports\Finance\Operate\OperateObject;
use Sports\Config\ConfigSingle;
use Sports\Constant\Finance;
use Sports\Exception\ItemNotExistException;
use Sports\Exception\LogicException;
use Zend\Db\Adapter\Adapter;


/**
 * 积分service
 * Class PointsService
 * @package Sports\Finance
 */
class PointsService extends BaseService
{
    /**
     * @var $sRate = ConfigSingle::get('point_rate');
     *      $aRates = json_decode($sRate, true);
     */
    private $aPointRateCfg = null;
    public function __construct(Adapter $oAdapter, $aPointRateCfg = null)
    {
        parent::__construct($oAdapter);

        $this->aPointRateCfg = $aPointRateCfg;
    }

    public function setPointRateCfg($aPointRateCfg)
    {
        $this->aPointRateCfg = $aPointRateCfg;
    }

    private $oFinanceService = null;
    public function getFinanceService()
    {
        if($this->oFinanceService === null){
            $this->oFinanceService = new FinanceService($this->getDbAdapter());
        }
        return $this->oFinanceService;
    }

    /**
     * @param $iUserId
     * @param $ePurpose
     * @return Account\AccountObject|null
     */
    public function getByUserId($iUserId)
    {
        return $this->getFinanceService()->getUserAccount($iUserId, Finance::PURPOSE_POINTS);
    }

    /**
     * 查询是否充值
     * @param $iUserId
     * @param $eRelationType
     * @param $iRelationId
     * @return Operate\ActionObject[]
     */
    public function checkIsEarned($iUserId, $eRelationType, $iRelationId)
    {
        $oAction = new ActionObject();
        $oAction->setUserId($iUserId)->setRelationId($iRelationId)->setRelationType($eRelationType)
            ->setPurpose(Finance::PURPOSE_POINTS)->setOperateType(Finance::OPERATE_RECHARGE);

        $oActionService = new ActionService($this->getDbAdapter());
        return $oActionService->query($oAction);
    }

    /**
     * 增加积分
     * @param $iUserId
     * @param $iAmount
     * @param $eRelationType
     * @param $iRelationId
     */
    public function earn($iUserId, $iAmount, $eRelationType, $iRelationId)
    {
        $oOperate = new OperateObject();
        $oOperate->setRelationId($iRelationId)->setRelationType($eRelationType);

        $iAmount = $this->calcRate($iAmount,$eRelationType);
        $oAction = new ActionObject();
        $oAction->setUserId($iUserId)->setAmount($iAmount)
            ->setPurpose(Finance::PURPOSE_POINTS)->setOperateType(Finance::OPERATE_RECHARGE);

        $oOperate->addAction($oAction);

        return $this->getFinanceService()->execute($oOperate);
    }

    /**
     * 撤销充值
     * @param $iUserId
     * @param $eRelationType
     * @param $iRelationId
     * @return Operate\ActionObject[]
     */
    public function reversalEarned($iUserId, $eRelationType, $iRelationId)
    {
        $oAction = new ActionObject();
        $oAction->setUserId($iUserId)->setRelationId($iRelationId)->setRelationType($eRelationType)
            ->setPurpose(Finance::PURPOSE_POINTS)->setOperateType(Finance::OPERATE_RECHARGE);

        $oActionService = new ActionService($this->getDbAdapter());
        $aActionHits = $oActionService->query($oAction);
        if(empty($aActionHits)){
            throw new ItemNotExistException(
                sprintf('record not existed,action: %s',$oAction->toArraySerializable()));
        }
        if(count($aActionHits) > 1){
            throw new LogicException(sprintf('records(%s) more than 1', count($aActionHits)));
        }

        $iOperateId = current($aActionHits)->getOperateId();
        $aOperateActions = $oActionService->getActionsByOperateId($iOperateId);
        if(count($aOperateActions) > 1){
            throw new LogicException(sprintf("operate(%s)'s actions(%s) more than 1",
                $iOperateId, count($aOperateActions)));
        }

        return $this->getFinanceService()->reversal($iOperateId);
    }

    /**
     * 减少积分
     * @param $iUserId
     * @param $iAmount
     * @param $eRelationType
     * @param $iRelationId
     */
    public function cost($iUserId, $iAmount, $eRelationType, $iRelationId)
    {
        $oOperate = new OperateObject();
        $oOperate->setRelationId($iRelationId)->setRelationType($eRelationType);

        $oAction = new ActionObject();
        $iAmount = $this->calcRate($iAmount,$eRelationType);
        $oAction->setUserId($iUserId)->setAmount($iAmount)
            ->setPurpose(Finance::PURPOSE_POINTS)->setOperateType(Finance::OPERATE_CONSUME);

        $oOperate->addAction($oAction);

        return $this->getFinanceService()->execute($oOperate);
    }

    /**
     * 计算利润率
     * @param $iAmount
     * @param $eRelationType
     * @return mixed
     * @throws \Sports\Exception\LogicException
     */
    private function calcRate($iAmount, $eRelationType)
    {
        if(!isset($this->aPointRateCfg[$eRelationType])){
            throw new LogicException(sprintf('rate of relation type(%s) is not existed(%s)', $eRelationType));
        }

        return $iAmount * $this->aPointRateCfg[$eRelationType];
    }
}
