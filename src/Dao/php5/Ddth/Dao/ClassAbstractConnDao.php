<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Abstract connection implementation of {@link Ddth_Dao_IDao}.
 *
 * LICENSE: See the included license.txt file for detail.
 *
 * COPYRIGHT: See the included copyright.txt file for detail.
 *
 * @package     Dao
 * @author      Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version     $Id$
 * @since       File available since v0.2.1
 */

/**
 * Abstract connection implementation of {@link Ddth_Dao_IDao}.
 *
 * This abstract implementation of {@link Ddth_Dao_IDao} delegates calls to {@link getConnection()}
 * and {@link closeConnection()} to its factory.
 * This class is meant to be used together with {@link Ddth_Dao_AbstractConnDaoFactory}.
 *
 * @package    	Dao
 * @author     	Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @since      	Class available since v0.2.1
 */
abstract class Ddth_Dao_AbstractConnDao extends Ddth_Dao_AbstractDao {
    /**
     * @see Ddth_Dao_IDao::getConnection()
     */
    public function getConnection($startTransaction = FALSE) {
        $conn = $this->getDaoFactory()->getConnection($startTransaction);
        if ($conn === NULL || $conn === FALSE) {
            throw new Ddth_Dao_DaoException('Failed to obtain a db connection!');
        }
        return $conn;
    }

    /**
     * @see Ddth_Dao_IDao::closeConnection()
     */
    public function closeConnection($hasError = FALSE, $forceClose = FALSE) {
        $this->getDaoFactory()->closeConnection($hasError, $forceClose);
    }

    /**
     * Executes a query and returns the result set.
     *
     * This function just simply returns FALSE. Sub-class should have its own implement.
     * For example, MySQL sub-class can use the function mysql_query() to execute the query.
     *
     * @param string $query
     * @return resource
     * @since 0.2.3
     */
    protected function executeQuery($query) {
        return FALSE;
    }

    /**
     * Frees a result set memory.
     *
     * This function simply returns FALSE. Sub-classes should have its own implementation.
     * For example, MySQL sub-class may use the function mysql_free_result to free the
     * result set memory.
     *
     * @param resource $resultSet
     * @return bool
     */
    protected function freeResult($resultSet) {
        return FALSE;
    }
}
?>
