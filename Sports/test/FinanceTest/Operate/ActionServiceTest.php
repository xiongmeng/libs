<?php
/**
 * 
 */

namespace Sports\FinanceTest\Account;

use Sports\Finance\Operate\ActionObject;
use Sports\Finance\Operate\ActionService;

require_once __DIR__ . '/../../BaseServiceTest.php';

class OperateServiceTest extends \BaseServiceTest
{
    /**
     * @test
     */
    public function createAndGet()
    {
        $this->oTransaction->startTransaction();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)
            ->setUserId(3)->setAmount(4)->setOperateId(1)->setOperateType(2);

        $iTimeBefore = time();

        $oOperateService = new ActionService($this->oDbAdapter);
        $iActionId = $oOperateService->create($oAction);

        $iTimeAfter = time();

        $oActionAfter = $oOperateService->getById($iActionId);

        $this->assertEquals($iActionId, $oActionAfter->getId());
        $this->assertEquals($oAction->getPurpose(), $oActionAfter->getPurpose());
        $this->assertEquals($oAction->getRelationId(), $oActionAfter->getRelationId());
        $this->assertEquals($oAction->getRelationType(), $oActionAfter->getRelationType());
        $this->assertEquals($oAction->getUserId(), $oActionAfter->getUserId());
        $this->assertEquals($oAction->getAmount(), $oActionAfter->getAmount());
        $this->assertEquals($oAction->getOperateId(), $oActionAfter->getOperateId());
        $this->assertEquals($oAction->getOperateType(), $oActionAfter->getOperateType());
        $iTime = $oActionAfter->getCreatedTime();
        $this->assertEquals(true, $iTimeBefore <= $iTime && $iTime <= $iTimeAfter);

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function getActionByOperateIdNotExisted()
    {
        $this->oTransaction->startTransaction();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)
            ->setUserId(3)->setAmount(4)->setOperateId(1)->setOperateType(2);

        $oOperateService = new ActionService($this->oDbAdapter);
        $iActionId = $oOperateService->create($oAction);

        $aActionList = $oOperateService->getActionsByOperateId($oAction->getOperateId() + 1);
        $this->assertEquals(0, count($aActionList));

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function getActionsByOperateIdWithOne()
    {
        $this->oTransaction->startTransaction();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)
            ->setUserId(3)->setAmount(4)->setOperateId(1)->setOperateType(2);

        $iTimeBefore = time();

        $oOperateService = new ActionService($this->oDbAdapter);
        $iActionId = $oOperateService->create($oAction);

        $iTimeAfter = time();

        $aActionList = $oOperateService->getActionsByOperateId($oAction->getOperateId());
        $this->assertEquals(1, count($aActionList));

        $oActionAfter = current($aActionList);
        $this->assertEquals($iActionId, $oActionAfter->getId());
        $this->assertEquals($oAction->getPurpose(), $oActionAfter->getPurpose());
        $this->assertEquals($oAction->getRelationId(), $oActionAfter->getRelationId());
        $this->assertEquals($oAction->getRelationType(), $oActionAfter->getRelationType());
        $this->assertEquals($oAction->getUserId(), $oActionAfter->getUserId());
        $this->assertEquals($oAction->getAmount(), $oActionAfter->getAmount());
        $this->assertEquals($oAction->getOperateId(), $oActionAfter->getOperateId());
        $this->assertEquals($oAction->getOperateType(), $oActionAfter->getOperateType());
        $iTime = $oActionAfter->getCreatedTime();
        $this->assertEquals(true, $iTimeBefore <= $iTime && $iTime <= $iTimeAfter);

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function getActionsByOperateIdMoreThanOne()
    {
        $this->oTransaction->startTransaction();

        $oAction = new ActionObject();
        $oAction->setPurpose(1)->setRelationId(2)->setRelationType(2)
            ->setUserId(3)->setAmount(4)->setOperateId(1)->setOperateType(2);

        $oOperateService = new ActionService($this->oDbAdapter);
        $oOperateService->create($oAction);
        $oOperateService->create($oAction);

        $aActionList = $oOperateService->getActionsByOperateId($oAction->getOperateId());
        $this->assertEquals(2, count($aActionList));

        $this->oTransaction->rollback();
    }
}