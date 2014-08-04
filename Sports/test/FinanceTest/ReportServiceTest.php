<?php
namespace FinanceTest\Service;

use Sports\Constant\Finance;
use Sports\Finance\ReportService;

require_once __DIR__ . '/../BaseServiceTest.php';

class ReportServiceTest extends \BaseServiceTest
{
    public function testQueryBilling()
    {
        $oReportService = new ReportService($this->oDbAdapter);

        $aResult = $oReportService->queryBilling(array('user_id' => 889082, 'purpose' => Finance::PURPOSE_POINTS), 1, 10);

        print_r($aResult);
    }

    public function testQueryBillingTotal()
    {
        $oReportService = new ReportService($this->oDbAdapter);

        $aResult = $oReportService->queryBillingTotal(array('hall_id' => 8900));

        print_r($aResult);
    }

    public function testQueryBillingStatistics()
    {
        $oReportService = new ReportService($this->oDbAdapter);

        $aResult = $oReportService->queryBillingStatistics(array('user_id' => 889082, 'purpose' => Finance::PURPOSE_ACCOUNT));

        print_r($aResult);
    }
}