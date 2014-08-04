<?php
namespace Sports\Finance\Account;
use Sports\Finance\BaseService;
use Sports\Finance\Operate\ActionObject;
use Sports\Constant\Finance;
use Sports\Exception\ItemNotExistException;
use Sports\Exception\LogicException;
use Sports\Exception\NotSupportException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;


/**
 * 提供对Account的操作
 * Class PointsService
 * @package Sports\Finance
 */
class AccountService extends BaseService
{

    /**
     * 创建一个Account信息
     */
    public function create($iUserId=null, $ePurpose=null)
    {
        $oDbTable = new TableGateway(Finance::TABLE_ACCOUNT, $this->getDbAdapter());
        $oDbTable->insert(array('created_time' => time(), 'user_id' => $iUserId, 'purpose' => $ePurpose));
        return $oDbTable->getLastInsertValue();
    }

    public function getByUserIdAndPurpose($iUserId, $ePurpose)
    {
        $oDbTable = new TableGateway(Finance::TABLE_ACCOUNT,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('user_id', $iUserId)->equalTo('purpose', $ePurpose);

        $oDbResult = $oDbTable->select($oWhere);

        return $oDbResult->count() > 0 ? new AccountObject($oDbResult->current()) : null;
    }

    /**
     * @param $iAccountId
     * @return AccountObject|null
     */
    public function getById($iAccountId)
    {
        $oDbTable = new TableGateway(Finance::TABLE_ACCOUNT,
            $this->getDbAdapter(), null, new ResultSet(ResultSet::TYPE_ARRAY));

        $oWhere = new Where();
        $oWhere->equalTo('id', $iAccountId);

        $oDbResult = $oDbTable->select($oWhere);

        return $oDbResult->count() > 0 ? new AccountObject($oDbResult->current()) : null;
    }

    /**
     * 充值
     * @param $iAccountId
     * @param $iNum
     * @param $eRelationType
     * @param $iRelationId
     */
    public function recharge($iAccountId, ActionObject $oAction)
    {
        $oOriAccount = $this->getById($iAccountId);
        if (empty($oOriAccount)) {
            throw new ItemNotExistException('not found account : ' . $iAccountId);
        }

        $oDbTable = new TableGateway(Finance::TABLE_ACCOUNT, $this->getDbAdapter());

        $oWhere = new Where();
        $oWhere->equalTo('id', $iAccountId);

        $oUpdate = $oDbTable->getSql()->update();
        $oUpdate->where($oWhere);
        $oUpdate->set(
            array('balance' => new Expression("balance + ?", $oAction->getAmount())));

        $res = $oDbTable->updateWith($oUpdate);
        if (!$res) {
            throw new LogicException("fail while update");
        }

        $oCurAccount = $this->getById($iAccountId);

        return $this->createBalanceBilling($oOriAccount, $oCurAccount, $oAction);
    }

    /**
     * 消费
     * @param $iAccountId
     * @param $iNum
     * @param $eRelationType
     * @param $iRelationId
     */
    public function consume($iAccountId, ActionObject $oAction)
    {
        $oOriAccount = $this->getById($iAccountId);
        if (!$oOriAccount) {
            throw new ItemNotExistException('not found account : ' . $iAccountId);
        }

        $fChangeAmount = $oAction->getAmount();
        if ($oOriAccount->getAvailableAmount() < $fChangeAmount) {
            throw new LogicException("not sufficient funds");
        }

        $oDbTable = new TableGateway(Finance::TABLE_ACCOUNT, $this->getDbAdapter());

        $oWhere = new Where();
        $oWhere->equalTo('id', $iAccountId);
        $oWhere->expression('balance-freeze+credit>=?', $fChangeAmount);

        $oUpdate = $oDbTable->getSql()->update();
        $oUpdate->set(array('balance' => new Expression("balance - ?", $fChangeAmount)));
        $oUpdate->where($oWhere);

        $res = $oDbTable->updateWith($oUpdate);
        if (!$res) {
            throw new LogicException("fail while update");
        }

        $oCurAccount = $this->getById($iAccountId);

        return $this->createBalanceBilling($oOriAccount, $oCurAccount, $oAction);
    }

    /**
     * 冻结
     * @param $iAccountId
     * @param $iNum
     * @param $eRelationType
     * @param $iRelationId
     */
    public function freeze($iAccountId, ActionObject $oAction)
    {
        $oOriAccount = $this->getById($iAccountId);
        if (!$oOriAccount) {
            throw new ItemNotExistException('not found account : ' . $iAccountId);
        }

        $fChangeAmount = $oAction->getAmount();
        if ($oOriAccount->getAvailableAmount() < $fChangeAmount) {
            throw new LogicException("not sufficient funds");
        }

        $oDbTable = new TableGateway(Finance::TABLE_ACCOUNT, $this->getDbAdapter());

        $oWhere = new Where();
        $oWhere->equalTo('id', $iAccountId);
        $oWhere->expression('balance-freeze+credit>=?', $fChangeAmount);

        $oUpdate = $oDbTable->getSql()->update();
        $oUpdate->set(array('freeze' => new Expression("freeze + ?", $fChangeAmount)));
        $oUpdate->where($oWhere);

        $res = $oDbTable->updateWith($oUpdate);
        if (!$res) {
            throw new LogicException("fail while update");
        }

        $oCurAccount = $this->getById($iAccountId);

        return $this->createFreezeBilling($oOriAccount, $oCurAccount, $oAction);
    }

    /**
     * 解冻
     * @param $iAccountId
     * @param $iNum
     * @param $eRelationType
     * @param $iRelationId
     */
    public function unfreeze($iAccountId, ActionObject $oAction)
    {
        $oOriAccount = $this->getById($iAccountId);
        if (!$oOriAccount) {
            throw new ItemNotExistException('not found account : ' . $iAccountId);
        }

        $fChangeAmount = $oAction->getAmount();
        if($oOriAccount->getFreeze() < $fChangeAmount){
            throw new LogicException("not enough freezeAmount");
        }

        $oWhere = new Where();
        $oWhere->equalTo('id', $iAccountId);
        $oWhere->expression('freeze>=?', $fChangeAmount);

        $oDbTable = new TableGateway(Finance::TABLE_ACCOUNT, $this->getDbAdapter());

        $oUpdate = $oDbTable->getSql()->update();
        $oUpdate->set(array('freeze' => new Expression("freeze - ?", $fChangeAmount)));
        $oUpdate->where($oWhere);

        $res = $oDbTable->updateWith($oUpdate);
        if (!$res) {
            throw new LogicException("fail while update");
        }

        $oCurAccount = $this->getById($iAccountId);

        return $this->createFreezeBilling($oOriAccount, $oCurAccount, $oAction);
    }

    /**
     * 提升信用
     * @param $iAccountId
     * @param $iNum
     */
    public function enhanceCredit($iAccountId, $iNum)
    {
        throw new NotSupportException();
    }

    /**
     * 降低信用
     */
    public function reduceCredit($iAccountId, $iNum)
    {
        throw new NotSupportException();
    }

    /**
     * 创建余额流水
     * @param AccountObject $oOriAccount
     * @param AccountObject $oCurAccount
     * @param ActionObject $oAction
     * @return int
     */
    private function createBalanceBilling(
        AccountObject $oOriAccount, AccountObject $oCurAccount, ActionObject $oAction)
    {
        $oBilling = new BillingObject();
        $oBilling->setAccountId($oCurAccount->getId())
            ->setType(Finance::ACCOUNT_BALANCE)
            ->setActionId($oAction->getId())
            ->setAccountBefore($oOriAccount->getBalance())
            ->setAccountChange($oAction->getAmount())
            ->setAccountAfter($oCurAccount->getBalance())
            ->setRelationId($oAction->getRelationId())
            ->setRelationType($oAction->getRelationType());

        $oBillingService = new BillingService($this->getDbAdapter());
        return $oBillingService->create($oBilling);
    }

    /**
     * 创建余额流水
     * @param AccountObject $oOriAccount
     * @param AccountObject $oCurAccount
     * @param ActionObject $oAction
     * @return int
     */
    private function createFreezeBilling(
        AccountObject $oOriAccount, AccountObject $oCurAccount, ActionObject $oAction)
    {
        $oBilling = new BillingObject();
        $oBilling->setAccountId($oCurAccount->getId())
            ->setType(Finance::ACCOUNT_FREEZE)
            ->setActionId($oAction->getId())
            ->setAccountBefore($oOriAccount->getFreeze())
            ->setAccountChange($oAction->getAmount())
            ->setAccountAfter($oCurAccount->getFreeze())
            ->setRelationId($oAction->getRelationId())
            ->setRelationType($oAction->getRelationType());

        $oBillingService = new BillingService($this->getDbAdapter());
        return $oBillingService->create($oBilling);
    }
}
