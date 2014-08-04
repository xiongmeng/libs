<?php
/**
 * 
 */

namespace FinanceTest\Service;

use Sports\Constant\Finance;
use Sports\Finance\Account\AccountService;
use Sports\Finance\Account\RelationService;
use Sports\Finance\FinanceService;
use Sports\Finance\Operate\ActionObject;
use Sports\Finance\Operate\ActionService;
use Sports\Finance\Operate\OperateObject;
use Sports\Finance\Operate\OperateService;

require_once __DIR__ . '/../BaseServiceTest.php';

class FinanceServiceTest extends \BaseServiceTest
{

    /**
     * @test
     */
    public function ensureAccountExist()
    {
        $this->oTransaction->startTransaction();

        $iUserId = 1; $ePurpose =1 ;
        $oRelationService = new RelationService($this->oDbAdapter);
        $oRelation = $oRelationService->loadByUserIdAndPurpose($iUserId, $ePurpose);
        $this->assertEquals(true, empty($oRelation));

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $oRelation = $oFinanceService->ensureAccountExisted($iUserId, $ePurpose);

        $oRelation = $oRelationService->getById($oRelation->getId());
        $this->assertEquals(true, !empty($oRelation));

        $oAccountService = new AccountService($this->oDbAdapter);
        $oAccount = $oAccountService->getById($oRelation->getAccountId());
        $this->assertEquals(true, !empty($oAccount));

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function executeActionOfRecharge()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_RECHARGE);

        $iActionId = $oFinanceService->executionAction($oAction);

        $oActionService = new ActionService($this->oDbAdapter);
        $oAction = $oActionService->getById($iActionId);
        $this->assertEquals(true, !empty($oAction));

        $oAccount = $oFinanceService
            ->getUserAccount($oAction->getUserId(), $oAction->getPurpose());
        $this->assertEquals($oAction->getAmount(), $oAccount->getBalance());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function executeActionOfConsume()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_RECHARGE);
        $iActionId = $oFinanceService->executionAction($oAction);

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_CONSUME);
        $iActionId = $oFinanceService->executionAction($oAction);

        $oActionService = new ActionService($this->oDbAdapter);
        $oAction = $oActionService->getById($iActionId);
        $this->assertEquals(true, !empty($oAction));

        $oAccount = $oFinanceService
            ->getUserAccount($oAction->getUserId(), $oAction->getPurpose());
        $this->assertEquals(0.0, $oAccount->getBalance());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function executeActionOfFreeze()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_RECHARGE);
        $iActionId = $oFinanceService->executionAction($oAction);

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_FREEZE);
        $iActionId = $oFinanceService->executionAction($oAction);

        $oActionService = new ActionService($this->oDbAdapter);
        $oAction = $oActionService->getById($iActionId);
        $this->assertEquals(true, !empty($oAction));

        $oAccount = $oFinanceService
            ->getUserAccount($oAction->getUserId(), $oAction->getPurpose());
        $this->assertEquals($oAction->getAmount(), $oAccount->getBalance());
        $this->assertEquals($oAction->getAmount(), $oAccount->getFreeze());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function executeActionOfUnFreeze()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_RECHARGE);
        $iActionId = $oFinanceService->executionAction($oAction);

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_FREEZE);
        $iActionId = $oFinanceService->executionAction($oAction);

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)->setUserId($iUserId)
            ->setAmount(4)->setOperateId(1)->setOperateType(Finance::OPERATE_UNFREEZE);
        $iActionId = $oFinanceService->executionAction($oAction);

        $oActionService = new ActionService($this->oDbAdapter);
        $oAction = $oActionService->getById($iActionId);
        $this->assertEquals(true, !empty($oAction));

        $oAccount = $oFinanceService
            ->getUserAccount($oAction->getUserId(), $oAction->getPurpose());
        $this->assertEquals($oAction->getAmount(), $oAccount->getBalance());
        $this->assertEquals(0.0, $oAccount->getFreeze());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function executeWithNoAction()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oOperate = new OperateObject();
        $oOperate->setRelationId(1);
        $oOperate->setRelationType(1);

        try{
            $iOperateId = $oFinanceService->execute($oOperate);
            $this->oTransaction->rollback();
        }catch (\Exception $e){
            $this->assertEquals('no actions', $e->getMessage());
            return;
        }

        $this->fail('expected exception : no actions');
    }

    /**
     * @test
     */
    public function executeWithOneAction()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oOperate = new OperateObject();
        $oOperate->setRelationId(1);
        $oOperate->setRelationType(1);

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setUserId($iUserId)
            ->setAmount(4)->setOperateType(Finance::OPERATE_RECHARGE);

        $oOperate->addAction($oAction);

        $iOperateId = $oFinanceService->execute($oOperate);

        $oOperateService = new OperateService($this->oDbAdapter);
        $oOperate = $oOperateService->getById($iOperateId);
        $this->assertEquals(true, !empty($oOperate));

        $oActionService = new ActionService($this->oDbAdapter);
        $aActions = $oActionService->getActionsByOperateId($iOperateId);
        $this->assertEquals(1, count($aActions));
        $oAction = current($aActions);
        $this->assertEquals($oOperate->getRelationId(), $oAction->getRelationId());
        $this->assertEquals($oOperate->getRelationType(), $oAction->getRelationType());
        $this->assertEquals($iOperateId, $oAction->getOperateId());
    }

    /**
     * @test
     */
    public function executeWithMoreThanOneAction()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oOperate = new OperateObject();
        $oOperate->setRelationId(1);
        $oOperate->setRelationType(1);

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setUserId($iUserId)
            ->setAmount(4)->setOperateType(Finance::OPERATE_RECHARGE);

        $oOperate->addAction($oAction);
        $oOperate->addAction($oAction);

        $iOperateId = $oFinanceService->execute($oOperate);

        $oOperateService = new OperateService($this->oDbAdapter);
        $oOperate = $oOperateService->getById($iOperateId);
        $this->assertEquals(true, !empty($oOperate));

        $oActionService = new ActionService($this->oDbAdapter);
        $aActions = $oActionService->getActionsByOperateId($iOperateId);
        $this->assertEquals(2, count($aActions));
        $oAction = current($aActions);
        $this->assertEquals($oOperate->getRelationId(), $oAction->getRelationId());
        $this->assertEquals($oOperate->getRelationType(), $oAction->getRelationType());
        $this->assertEquals($iOperateId, $oAction->getOperateId());
    }

    /**
     * @test
     */
    public function reversal()
    {
        $this->oTransaction->startTransaction();

        $oFinanceService = new FinanceService($this->oDbAdapter);
        $iUserId = time();

        $oOperate = new OperateObject();
        $oOperate->setRelationId(1);
        $oOperate->setRelationType(1);

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setUserId($iUserId)
            ->setAmount(4)->setOperateType(Finance::OPERATE_RECHARGE);

        $oOperate->addAction($oAction);

        $iOperateId = $oFinanceService->execute($oOperate);

        $iReversalOperateId = $oFinanceService->reversal($iOperateId);

        $oOperateService = new OperateService($this->oDbAdapter);
        $oOperate = $oOperateService->getById($iReversalOperateId);
        $this->assertEquals(true, !empty($oOperate));
        $this->assertEquals($iOperateId, $oOperate->getRelationId());
        $this->assertEquals(Finance::RELATION_REVERSAL, $oOperate->getRelationType());

        $oActionService = new ActionService($this->oDbAdapter);
        $aActions = $oActionService->getActionsByOperateId($iReversalOperateId);
        $this->assertEquals(1, count($aActions));
        $oActionReversal = current($aActions);
        $this->assertEquals($oActionReversal->getAmount(), -1 * $oAction->getAmount());

        $oAccount = $oFinanceService
            ->getUserAccount($oAction->getUserId(), $oAction->getPurpose());
        $this->assertEquals(0.0, $oAccount->getBalance());
    }
}