<?php

namespace Jxc\Impl\Dao;

use Exception;
use Jxc\Impl\Core\MySQLDao;
use Jxc\Impl\Vo\LogInvokeApi;

/**
 * Api调用日志Dao
 */
final class LogInvokeApiDao extends MySQLDao {

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * 获取日志信息
     * @return array
     */
    public function select() {
        $resultSet = $this->mysqlDB()->select('log_invoke_api', '*', array());
        $array = array();
        foreach ($resultSet as $data) {
            $logInvokeApi = new LogInvokeApi();
            $logInvokeApi->convert($data);
            $array[$logInvokeApi->id] = $logInvokeApi;
        }
        return $array;
    }

    /**
     * @param LogInvokeApi $logInvokeApi
     * @return LogInvokeApi
     * @throws Exception
     */
    public function insert($logInvokeApi) {
        $query = $this->mysqlDB()->sqlInsert('log_invoke_api', $logInvokeApi->toArray());
        $this->mysqlDB()->ExecuteSQL($query);
        $logInvokeApi->id = $this->mysqlDB()->getInsertId();
        return $logInvokeApi;
    }
}