<?php

namespace Sports\FinanceTest\Account;

use Sports\Finance\Account\AccountService;
use Sports\Finance\Account\BillingService;
use Sports\Finance\Operate\ActionObject;

require_once __DIR__ . '/../BaseServiceTest.php';

class AccountServiceTest extends \BaseServiceTest
{

    /**
     * @test
     */
    public function createAndGet()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAccount = $oAccountService->getById($iAccountId);

        $this->assertEquals($iAccountId, $oAccount->getId());
        $this->assertEquals(0.0, $oAccount->getBalance());
        $this->assertEquals(0.0, $oAccount->getCredit());
        $this->assertEquals(0.0, $oAccount->getFreeze());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function recharge()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);

        $iBillingId = $oAccountService->recharge($iAccountId, $oAction);

        $oAccount = $oAccountService->getById($iAccountId);
        $this->assertEquals($oAction->getAmount(), $oAccount->getBalance());

        $oBillingService = new BillingService($this->oDbAdapter);
        $oBilling = $oBillingService->getById($iBillingId);
        $this->assertEquals($oAction->getId(), $oBilling->getActionId());
        $this->assertEquals($oAction->getRelationId(), $oBilling->getRelationId());
        $this->assertEquals($oAction->getRelationType(), $oBilling->getRelationType());
        $this->assertEquals(0.0, $oBilling->getAccountBefore());
        $this->assertEquals($oAction->getAmount(), $oBilling->getAccountChange());
        $this->assertEquals($oAccount->getBalance(), $oBilling->getAccountAfter());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function consumeWithEnoughBalance()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAction = new ActionObject();
        $oAction->setId(1)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        $oAccountService->recharge($iAccountId, $oAction);

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        $iBillingId = $oAccountService->consume($iAccountId, $oAction);

        $oAccount = $oAccountService->getById($iAccountId);
        $this->assertEquals(0.0, $oAccount->getBalance());

        $oBillingService = new BillingService($this->oDbAdapter);
        $oBilling = $oBillingService->getById($iBillingId);
        $this->assertEquals($oAction->getId(), $oBilling->getActionId());
        $this->assertEquals($oAction->getRelationId(), $oBilling->getRelationId());
        $this->assertEquals($oAction->getRelationType(), $oBilling->getRelationType());
        $this->assertEquals(10.0, $oBilling->getAccountBefore());
        $this->assertEquals($oAction->getAmount(), $oBilling->getAccountChange());
        $this->assertEquals($oAccount->getBalance(), $oBilling->getAccountAfter());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function consumeWithUnEnoughBalance()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        try{
            $oAccountService->consume($iAccountId, $oAction);
        }catch (\Exception $e){
            $this->assertEquals('not sufficient funds', $e->getMessage());
            $this->oTransaction->rollback();
            return;
        }

        $this->fail('expected exception "not sufficient funds"!');
    }

    /**
     * @test
     */
    public function freezeWithEnoughBalance()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAction = new ActionObject();
        $oAction->setId(1)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        $oAccountService->recharge($iAccountId, $oAction);

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        $iBillingId = $oAccountService->freeze($iAccountId, $oAction);

        $oAccount = $oAccountService->getById($iAccountId);
        $this->assertEquals($oAction->getAmount(), $oAccount->getFreeze());

        $oBillingService = new BillingService($this->oDbAdapter);
        $oBilling = $oBillingService->getById($iBillingId);
        $this->assertEquals($oAction->getId(), $oBilling->getActionId());
        $this->assertEquals($oAction->getRelationId(), $oBilling->getRelationId());
        $this->assertEquals($oAction->getRelationType(), $oBilling->getRelationType());
        $this->assertEquals(0.0, $oBilling->getAccountBefore());
        $this->assertEquals($oAction->getAmount(), $oBilling->getAccountChange());
        $this->assertEquals($oAccount->getFreeze(), $oBilling->getAccountAfter());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function freezeWithUnEnoughBalance()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        try{
            $oAccountService->freeze($iAccountId, $oAction);
        }catch (\Exception $e){
            $this->assertEquals('not sufficient funds', $e->getMessage());
            $this->oTransaction->rollback();
            return;
        }

        $this->fail('expected exception "not sufficient funds"!');
    }

    /**
     * @test
     */
    public function unfreezeWithEnoughFreeze()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAction = new ActionObject();
        $oAction->setId(1)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        $oAccountService->recharge($iAccountId, $oAction);

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        $oAccountService->freeze($iAccountId, $oAction);

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        $iBillingId = $oAccountService->unfreeze($iAccountId, $oAction);

        $oAccount = $oAccountService->getById($iAccountId);
        $this->assertEquals(0.0, $oAccount->getFreeze());

        $oBillingService = new BillingService($this->oDbAdapter);
        $oBilling = $oBillingService->getById($iBillingId);
        $this->assertEquals($oAction->getId(), $oBilling->getActionId());
        $this->assertEquals($oAction->getRelationId(), $oBilling->getRelationId());
        $this->assertEquals($oAction->getRelationType(), $oBilling->getRelationType());
        $this->assertEquals(10.0, $oBilling->getAccountBefore());
        $this->assertEquals($oAction->getAmount(), $oBilling->getAccountChange());
        $this->assertEquals($oAccount->getFreeze(), $oBilling->getAccountAfter());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function unfreezeWithUnEnoughFreeze()
    {
        $this->oTransaction->startTransaction();

        $oAccountService = new AccountService($this->oDbAdapter);
        $iAccountId = $oAccountService->create();

        $oAction = new ActionObject();
        $oAction->setId(2)->setAmount(10.0)->setRelationId(3)->setRelationType(4);
        try{
            $oAccountService->unfreeze($iAccountId, $oAction);
        }catch (\Exception $e){
            $this->assertEquals('not enough freezeAmount', $e->getMessage());
            $this->oTransaction->rollback();
            return;
        }

        $this->fail('expected exception "not enough freezeAmount"!');
    }
}