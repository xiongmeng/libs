<?php
/**
 * 
 */

namespace Sports\FinanceTest\Account;

use Sports\Finance\Operate\OperateObject;
use Sports\Finance\Operate\OperateService;

require_once __DIR__ . '/../../BaseServiceTest.php';

class OperateServiceTest extends \BaseServiceTest
{
    /**
     * @test
     */
    public function createAndGet()
    {
        $this->oTransaction->startTransaction();

        $oOperate = new OperateObject();
        $oOperate->setRelationType(1)->setRelationId(2);

        $iTimeBefore = time();

        $oOperateService = new OperateService($this->oDbAdapter);
        $iOperateId = $oOperateService->create($oOperate);

        $iTimeAfter = time();

        $oOperateAfter = $oOperateService->getById($iOperateId);

        $this->assertEquals($iOperateId, $oOperateAfter->getId());
        $this->assertEquals($oOperate->getRelationId(), $oOperateAfter->getRelationId());
        $this->assertEquals($oOperate->getRelationType(), $oOperateAfter->getRelationType());
        $iTime = $oOperateAfter->getCreatedTime();
        $this->assertEquals(true, $iTimeBefore <= $iTime && $iTime <= $iTimeAfter);

        $this->oTransaction->rollback();
    }
}