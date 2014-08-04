<?php
use Sports\Utility\Transaction;
use Zend\Db\Adapter\Adapter;

class BaseServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Transaction
     */
    protected  $oTransaction = null;
    protected  $oDbAdapter = null;
    public function setUp()
    {
        parent::setUp();

        $this->oDbAdapter= new Adapter(array(
            "driver" => "Mysqli",
            "database" => "gotennis",
            "username" => "homestead",
            "password" => "secret",
            "host" => "127.0.0.1",
            "port" => "33060",
            "charset" => "utf8",
            "options" => array(
                "buffer_results" => true
            )
        ));

        $this->oTransaction = Transaction::getInstance($this->oDbAdapter);
    }

}