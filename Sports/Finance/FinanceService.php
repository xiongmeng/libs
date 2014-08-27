<?php
/**
 *
 */

namespace Sports\Finance;

use Sports\Finance\Account\AccountService;
use Sports\Finance\Operate\ActionObject;
use Sports\Finance\Operate\ActionService;
use Sports\Finance\Operate\OperateObject;
use Sports\Finance\Operate\OperateService;
use Sports\Constant\Finance;
use Sports\Exception\ItemNotExistException;

use Sports\Exception\LogicException;
use Sports\Exception\ParamsInvalidException;
use Zend\Db\Adapter\Adapter;

class FinanceService extends BaseService
{
    private $autoTransaction;
    public function __construct(Adapter $adapter, $autoTransaction = true)
    {
        parent::__construct($adapter);

        $this->autoTransaction = $autoTransaction;
    }
    /**
     * @param $iUserId
     * @param $ePurpose
     * @return null|\Sports\Finance\Account\AccountObject
     */
    public function ensureAccountExisted($iUserId, $ePurpose)
    {
        $oAccountService = new AccountService($this->getDbAdapter());

        $oAccount = $oAccountService->getByUserIdAndPurpose($iUserId, $ePurpose);
        if(empty($oAccount)){
            $iAccountId = $oAccountService->create($iUserId, $ePurpose);
            $oAccount = $oAccountService->getById($iAccountId);
        }

        return $oAccount;
    }


    /**
     * @param $iUserId
     * @param $ePurpose
     * @return \Sports\Finance\Account\AccountObject|null
     */
    public function getUserAccount($iUserId, $ePurpose)
    {
        $oAccountService = new AccountService($this->getDbAdapter());
        return $oAccountService->getByUserIdAndPurpose($iUserId, $ePurpose);
    }

    /**
     * @param ActionObject $oAction
     * @return int
     * @throws \Sports\Exception\LogicException
     */
    public function executionAction(ActionObject $oAction)
    {
        $oRelation = $this
            ->ensureAccountExisted($oAction->getUserId(), $oAction->getPurpose());
        $iAccountId = $oRelation->getId();

        //记录Action
        $oActionService = new ActionService($this->getDbAdapter());
        $iActionId = $oActionService->create($oAction);
        $oAction->setId($iActionId);

        //执行操作
        $oAccountService = new AccountService($this->getDbAdapter());
        switch($oAction->getOperateType()){
            case Finance::OPERATE_RECHARGE:
                $oAccountService->recharge($iAccountId, $oAction);
                break;
            case Finance::OPERATE_CONSUME:
                $oAccountService->consume($iAccountId, $oAction);
                break;
            case Finance::OPERATE_FREEZE:
                $oAccountService->freeze($iAccountId, $oAction);
                break;
            case Finance::OPERATE_UNFREEZE:
                $oAccountService->unfreeze($iAccountId, $oAction);
                break;
            default:
                throw new LogicException('unSupport operate');
        }

        return $iActionId;
    }

    /**
     * 执行操作
     * @param OperateObject $oOperate
     */
    public function execute(OperateObject $oOperate)
    {
        $this->autoTransaction && $this->transaction->startTransaction();
        try{
            //记录操作
            $oOperateService = new OperateService($this->getDbAdapter());
            $iOperateId = $oOperateService->create($oOperate);

            //依次执行每个Action
            $aActions = $oOperate->getActions();
            if(count($aActions) <= 0){
                throw new ParamsInvalidException('no actions');
            }

            foreach($aActions as $oAction){
                $oAction->setOperateId($iOperateId)->setRelationId($oOperate->getRelationId());

                $relationType = $oAction->getRelationType();
                empty($relationType) && $oAction->setRelationType($oOperate->getRelationType());

                $this->executionAction($oAction);
            }

            $this->autoTransaction && $this->transaction->commit();
            return $iOperateId;
        }catch (\Exception $e){
            $this->autoTransaction && $this->transaction->rollback();
            throw $e;
        }
    }

    /**
     * 撤销操作
     * @param $iOperationId
     * @return int
     * @throws \Sports\Exception\ItemNotExistException
     */
    public function reversal($iOperationId)
    {
        $oOperateService = new OperateService($this->getDbAdapter());
        $oOperate = $oOperateService->getById($iOperationId);
        if (!$oOperate) {
            throw new ItemNotExistException("not found OperationId: " . $iOperationId);
        }

        $oActionService = new ActionService($this->getDbAdapter());
        $aActions = $oActionService->getActionsByOperateId($iOperationId);
        if(empty($aActions)){
            throw new ItemNotExistException(
                "not found actions of OperationId: " . $iOperationId);
        }

        foreach($aActions as $oAction){
            $oAction->setAmount(-1 * $oAction->getAmount());
            $oOperate->addAction($oAction);
        }

        $oOperate->setRelationId($oOperate->getId())
            ->setRelationType(Finance::RELATION_REVERSAL);

        return $this->execute($oOperate);
    }
}
