<?php
/**
 * 
 */

namespace Sports\FinanceTest\Account;

use Sports\Finance\Account\BillingObject;
use Sports\Finance\Account\BillingService;

require_once __DIR__ . '/../BaseServiceTest.php';

class BillingServiceTest extends \BaseServiceTest
{

    /**
     * @test
     */
    public function createAndGet()
    {
        $this->oTransaction->startTransaction();

        $oBilling = new BillingObject();
        $oBilling->setRelationType(1)->setRelationId(2)->setAccountId(3)->setActionId(4)
            ->setType(5)->setAccountAfter(10.0)->setAccountChange(5.0)->setAccountBefore(5.0);

        $iTimeBefore = time();

        $oBillingService = new BillingService($this->oDbAdapter);
        $iBillingId = $oBillingService->create($oBilling);

        $iTimeAfter = time();

        $oBillingAfter = $oBillingService->getById($iBillingId);

        $this->assertEquals($iBillingId, $oBillingAfter->getId());
        $this->assertEquals($oBilling->getAccountAfter(), $oBillingAfter->getAccountAfter());
        $this->assertEquals($oBilling->getAccountBefore(), $oBillingAfter->getAccountBefore());
        $this->assertEquals($oBilling->getAccountChange(), $oBillingAfter->getAccountChange());
        $this->assertEquals($oBilling->getRelationId(), $oBillingAfter->getRelationId());
        $this->assertEquals($oBilling->getRelationType(), $oBillingAfter->getRelationType());
        $this->assertEquals($oBilling->getAccountId(), $oBillingAfter->getAccountId());
        $this->assertEquals($oBilling->getActionId(), $oBillingAfter->getActionId());
        $iTime = $oBillingAfter->getCreatedTime();
        $this->assertEquals(true, $iTimeBefore <= $iTime && $iTime <= $iTimeAfter);

        $this->oTransaction->rollback();
    }
}