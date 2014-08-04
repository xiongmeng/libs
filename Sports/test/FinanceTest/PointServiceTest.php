<?php
namespace FinanceTest\Service;
use Sports\Finance\PointsService;

require_once __DIR__ . '/../BaseServiceTest.php';

class PointsServiceTest extends \BaseServiceTest
{
    private $aPointRateCfg = array("1" => 1, "5" => 1, "6" => 1);
    /**
     * @test
     */
    public function earn()
    {
        $this->oTransaction->startTransaction();

        $oPointsService = new PointsService($this->oDbAdapter, $this->aPointRateCfg);
        $iUserId = time();

        $oPointsService->earn($iUserId, 10, 1, 1);

        $oAccount = $oPointsService->getByUserId($iUserId, 10);
        $this->assertEquals(10, $oAccount->getBalance());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function cost()
    {
        $this->oTransaction->startTransaction();

        $oPointsService = new PointsService($this->oDbAdapter, $this->aPointRateCfg);
        $iUserId = time();

        $oPointsService->earn($iUserId, 10, 1, 1);
        $oPointsService->cost($iUserId, 10, 1, 1);

        $oAccount = $oPointsService->getByUseId($iUserId, 10);
        $this->assertEquals(0, $oAccount->getBalance());

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function checkIsEarned()
    {
        $this->oTransaction->startTransaction();

        $oPointsService = new PointsService($this->oDbAdapter, $this->aPointRateCfg);
        $iUserId = time();

        $oPointsService->earn($iUserId, 10, 1, 1);

        $oAccount = $oPointsService->getByUseId($iUserId, 10);
        $this->assertEquals(10, $oAccount->getBalance());

        $aActions = $oPointsService->checkIsEarned($iUserId, 1, 1);
        $this->assertEquals(true, count($aActions) > 0);

        $this->oTransaction->rollback();
    }

    /**
     * @test
     */
    public function reversalEarned()
    {
        $this->oTransaction->startTransaction();

        $oPointsService = new PointsService($this->oDbAdapter, $this->aPointRateCfg);
        $iUserId = time();

        $oPointsService->earn($iUserId, 10, 1, 1);

        $oAccount = $oPointsService->getByUseId($iUserId, 10);
        $this->assertEquals(10, $oAccount->getBalance());

        $bResult = $oPointsService->reversalEarned($iUserId, 1, 1);
        $oAccount = $oPointsService->getByUseId($iUserId, 10);
        $this->assertEquals(0, $oAccount->getBalance());

        $this->oTransaction->rollback();
    }
}