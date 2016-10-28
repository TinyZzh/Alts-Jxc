<?php

namespace Jxc\Impl\Core;
use Exception;
use Jxc\Impl\Libs\JxcMySQLi;

/**
 * The MySQL database
 */
class MySQLDao {

    private $config;
    private static $dbs = array();

    /**
     * MySQLDao constructor.
     * @param $config
     */
    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * @return JxcMySQLi
     * @throws Exception
     */
    public function mysqlDB() {
        return self::getDB($this->config);
    }

    /**
     * Get database connect
     * @param array|null $config MYSQL connect config
     * @return JxcMySQLi
     * @throws Exception
     */
    public static function getDB($config = null) {
        if ($config == null) {
            throw new Exception("Database config is null.");
        }
        $database = $config['dbname'];
        $host = $config['host'];
        $hosts = isset(self::$dbs[$host]) ? self::$dbs[$host] : array();
        if (isset($hosts[$database])) {
            return $hosts[$database];
        }
        $db = new JxcMySQLi($host, $config['user'], $config['pwd'], $config['dbname']);
        $hosts[$database] = $db;
        self::$dbs[$host] = $hosts;
        return $db;
    }

    /**
     * Async query sql
     * @param string $sql The sql
     */
    public static function asyncExecuteSql($sql) {
//        self::getRedis()->list_lPush('sqls', $sql);
        self::getDB()->ExecuteSQL($sql);
    }

    public static $MULTI_QUERY = array();

    public static function multiQuery($type, $table, $data, $where) {
//        $MULTI_QUERY[$table][$type] =
    }

}
/* End of file MySQLDao.php */