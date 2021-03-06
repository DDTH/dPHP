<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * SQL query wrapper.
 *
 * LICENSE: See the included license.txt file for detail.
 *
 * COPYRIGHT: See the included copyright.txt file for detail.
 *
 * @package Dao
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version $Id: ClassSqlStatement.php 294 2011-09-12 12:30:53Z
 *          btnguyen2k@gmail.com $
 * @since File available since v0.2.2
 */

/**
 * SQL query wrapper.
 *
 * This class encapsulates an SQL statement, which is a SQL query with
 * placeholders.
 * For example: <i>SELECT * FROM tbl_user WHERE id=${id}</i>.
 *
 * Note: do NOT use quotes (' or ") around the place-holders. Single quotes (')
 * will be
 * automatically added when needed.
 *
 * Usage:
 * <code>
 * //obtain a db connection
 * $dbConn = ...;
 *
 * //construct a Ddth_Dao_SqlStatement object
 * $sql = 'SELECT * FROM tbl_user WHERE id=${id}';
 * $sqlStm = new Ddth_Dao_Mysql_MysqlSqlStatement($sql);
 *
 * //execute the query
 * $values = Array('id' => 1);
 * $result = $sqlStm->execute($dbConn, $values);
 *
 * //another way to execute the query
 * $values = Array('id' => 1);
 * $sql = $sqlStm->prepare($dbConn, $values);
 * $result = mysql_query($sql, $dbConn);
 * </code>
 *
 * Another example using {@link Ddth_Dao_SqlStatementFactory}:
 * <code>
 * //obtain a db connection
 * $dbConn = ...;
 *
 * $configFile = 'user-dao.sql.properties';
 * $dbConnFactory = Ddth_Dao_SqlStatementFactory::getInstance($configFile);
 *
 * $sqlStm = $dbConnFactory->getSqlStatement('selectUserById');
 * $values = Array('id' => 1);
 * $resultSet = $sqlStm->execute($dbConn, $value);
 * </code>
 *
 * @package Dao
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @since Class available since v0.2.2
 */
abstract class Ddth_Dao_SqlStatement {

    private $sql = '';

    /**
     *
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;

    /**
     * Constructs a new Ddth_Dao_SqlStatement object.
     *
     * @param string $sql
     */
    public function __construct($sql = '') {
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog(__CLASS__);
        $this->setSql($sql);
    }

    /**
     * Gets the sql query.
     *
     * @return string
     */
    public function getSql() {
        return $this->sql;
    }

    /**
     * Sets the sql command.
     *
     * @param
     *            string
     */
    public function setSql($sql) {
        $this->sql = $sql;
    }

    /**
     * Gets the 'null' literate.
     * This function returns <code>NULL</code>. Sub-class may override
     * the behavior of this function if needed.
     *
     * @return string
     */
    protected function getNullLiterate() {
        return 'NULL';
    }

    /**
     * Prepares the SQL statement.
     *
     * @param mixed $conn
     *            an open database connection
     * @param Array $values
     *            parameters to be bind to the query (an associative array)
     * @return string the prepared SQL statement
     */
    public function prepare($conn, $values = Array()) {
        $sql = $this->sql;
        foreach ($values as $key => $value) {
            if ($value instanceof Ddth_Dao_ParamAsIs) {
                $v = $value->getValue();
                if ($v === NULL) {
                    $v = $this->getNullLiterate();
                }
            } else if (is_array($value)) {
                $tempArr = Array();
                foreach ($value as $val) {
                    $v = $this->escape($conn, $val);
                    if ($v === NULL) {
                        $v = $this->getNullLiterate();
                    } else if ($val === '' || is_string($val)) {
                        $v = "'$v'";
                    }
                    $tempArr[] = $v;
                }
                $v = implode(',', $tempArr);
            } else {
                $v = $this->escape($conn, $value);
                if ($v === NULL) {
                    $v = $this->getNullLiterate();
                } else if ($value === '' || is_string($value)) {
                    $v = "'$v'";
                }
            }
            $sql = str_replace('${' . $key . '}', $v, $sql);
        }
        return $sql;
    }

    /**
     * Escapes a value to be used in a SQL statement.
     * Sub-class must implement this function.
     *
     * Note: sub-class should not add quotes (" or ') around the value, leave
     * that to
     * {@link prepare()} function.
     *
     * @param mixed $conn
     *            an open database connection
     * @param mixed $value
     *            the value to escape
     * @return string the escaped string
     */
    protected abstract function escape($conn, $value);

    /**
     * Prepares and executes the statement.
     *
     * @param mixed $conn
     *            an open database connection
     * @param Array $values
     *            parameters to be bind to the query (an associative array)
     * @return mixed
     */
    public function execute($conn, $values = Array()) {
        $sql = $this->prepare($conn, $values);
        if ($this->LOGGER->isDebugEnabled()) {
            $msg = '[' . __CLASS__ . '::' . __FUNCTION__ . "]Executing query: $sql";
            $this->LOGGER->debug($msg);
        }
        try {
            $timeBegin = microtime(TRUE);
            $result = $this->doExecute($sql, $conn);
            $timeEnd = microtime(TRUE);
            $queryInfo = Array($sql, $timeEnd - $timeBegin);
            Ddth_Dao_BaseDaoFactory::logQuery($queryInfo);
            return $result;
        } catch (Exception $e) {
            $msg = '[' . __CLASS__ . '::' . __FUNCTION__ . "]{$e->getMessage()}. Query: $sql";
            throw new Exception($msg);
        }
    }

    /**
     * Executes the prepared sql query.
     * Sub-class must implement this function.
     *
     * @param string $preparedSql
     *            the prepared sql query
     * @param mixed $conn
     *            an open database connection
     * @return mixed
     */
    protected abstract function doExecute($preparedSql, $conn);

    /**
     * Gets number of affected rows from previous operation.
     *
     * This function simply returns -1. Sub-class should override the function
     * to implements its own business.
     *
     * Depends on the underlying database, one of the parameters will be used.
     *
     * @param resource $conn
     *            database connection resource identifier
     * @param resource $result
     *            database query result resource identifier
     * @return int number of affected rows, FALSE if error, -1 if not supported
     * @since function available since v0.2.5
     */
    public function getNumAffectedRows($conn = NULL, $qres = NULL) {
        return -1;
    }
}
