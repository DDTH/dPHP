<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * PHPUnit (http://www.phpunit.de/) test case for UserDao (Mysql engine).
 *
 * LICENSE: See the included license.txt file for detail.
 *
 * COPYRIGHT: See the included copyright.txt file for detail.
 *
 * @author      Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version     $Id$
 * @since       File available since v0.2.3
 */

/**
 * Test cases for Mysql UserDao.
 *
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 */
class MysqlUserDaoTest extends PHPUnit_Framework_TestCase {
    protected function setup() {
        global $TEST_DATA;
        $TEST_DATA = Array(Array('id' => 1, 'username' => 'user1', 'email' => 'user1@domain.com'),
                Array('id' => 2, 'username' => 'user2', 'email' => 'user2@domain.com'),
                Array('id' => 3, 'username' => 'user3', 'email' => 'user3@domain.com'));

        parent::setUp();

        global $DPHP_DAO_CONFIG;
        $DPHP_DAO_CONFIG = Array('dphp-dao.factoryClass' => 'Ddth_Dao_Mysql_BaseMysqlDaoFactory',
                'dphp-dao.mysql.host' => '127.0.0.1',
                'dphp-dao.mysql.username' => 'phpunit',
                'dphp-dao.mysql.password' => 'phpunit',
                'dphp-dao.mysql.database' => 'phpunit',
                'dao.user' => 'MysqlUserDao');

        $factory = Ddth_Dao_BaseDaoFactory::getInstance();

        $mysqlConn = $factory->getConnection();
        $sql = 'DROP TABLE IF EXISTS tbl_user';
        if (!mysql_query($sql, $mysqlConn->getConn())) {
            throw new Ddth_Dao_DaoException(mysql_error($mysqlConn->getConn()));
        }

        $mysqlConn = $factory->getConnection();
        $sql = 'CREATE TABLE tbl_user (id INT PRIMARY KEY, username VARCHAR(32), email VARCHAR(64))';
        if (!mysql_query($sql, $mysqlConn->getConn())) {
            throw new Ddth_Dao_DaoException(mysql_error($mysqlConn->getConn()));
        }

        $factory->closeConnection();
    }

    /**
     * Tests UserDao: create new user.
     */
    public function testCreateUser() {
        $factory = Ddth_Dao_BaseDaoFactory::getInstance();
        $this->assertNotNull($factory, "Can not create DAO factory object!");

        $userDao = $factory->getDao('dao.user');
        $this->assertNotNull($userDao, "Can not get the user dao!");

        global $TEST_DATA;
        foreach ($TEST_DATA as $user) {
            $userDao->createUser($user['id'], $user['username'], $user['email']);
        }
        $this->assertEquals(count($TEST_DATA), $userDao->countUsers());
    }

    public function testGetUserById() {
        $this->testCreateUser();

        $factory = Ddth_Dao_BaseDaoFactory::getInstance();
        $this->assertNotNull($factory, "Can not create DAO factory object!");

        $userDao = $factory->getDao('dao.user');
        $this->assertNotNull($userDao, "Can not get the user dao!");

        global $TEST_DATA;
        foreach ($TEST_DATA as $user) {
            $dbUser = $userDao->getUserById($user['id']);
            $this->assertEquals($dbUser['id'], $user['id']);
            $this->assertEquals($dbUser['username'], $user['username']);
            $this->assertEquals($dbUser['email'], $user['email']);
        }
    }

    public function testDeleteUserById() {
        $this->testCreateUser();

        $factory = Ddth_Dao_BaseDaoFactory::getInstance();
        $this->assertNotNull($factory, "Can not create DAO factory object!");

        $userDao = $factory->getDao('dao.user');
        $this->assertNotNull($userDao, "Can not get the user dao!");

        global $TEST_DATA;
        $userDao->deleteUser($TEST_DATA[0]['id']);
        $this->assertEquals(count($TEST_DATA) - 1, $userDao->countUsers());

        foreach ($TEST_DATA as $user) {
            $dbUser = $userDao->getUserById($user['id']);
            if ($user['id'] === $TEST_DATA[0]['id']) {
                $this->assertNull($dbUser);
            } else {
                $this->assertEquals($dbUser['id'], $user['id']);
                $this->assertEquals($dbUser['username'], $user['username']);
                $this->assertEquals($dbUser['email'], $user['email']);
            }
        }
    }
}
?>
