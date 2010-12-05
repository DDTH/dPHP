<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Factory to create {@link Ddth_Dao_IDao} objects.
 *
 * LICENSE: See the included license.txt file for detail.
 *
 * COPYRIGHT: See the included copyright.txt file for detail.
 *
 * @package     Dao
 * @author      Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version     $Id: ClassIBoManager.php 150 2008-03-12 18:59:43Z nbthanh@vninformatics.com $
 * @since       File available since v0.1
 */

/**
 * Factory to create {@link Ddth_Dao_IDao} objects.
 *
 * @package     Dao
 * @author     	Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @since      	Class available since v0.1
 */
class Ddth_Dao_DaoFactory implements Ddth_Dao_IDaoFactory {

    private static $cache = Array();
    private $daoCache = Array();

    const DEFAULT_CONFIG_FILE = "dphp-dao.properties";

    /**
     * @var boolean
     */
    private $initialized = false;

    /**
     * @var Ddth_Commons_Properties
     */
    private $props = NULL;

    /**
     * Constructs a new Ddth_Dao_DaoFactory object.
     *
     * @param string $configFile
     */
    public function __construct($configFile = NULL) {
        $this->init($configFile);
    }

    /**
     * Gets an instance of DAO factory.
     *
     * @param string path to the configuration file.
     * @return Ddth_Dao_DaoFactory
     * @throws {@link Ddth_Dao_DaoException}
     */
    public static function getInstance($configFile = NULL) {
        if ( $configFile === NULL ) {
            return self::getInstance(self::DEFAULT_CONFIG_FILE);
        }
        $obj = isset(self::$cache[$configFile]) ? self::$cache[$configFile] : NULL;
        if ( $obj === NULL ) {
            $obj = new Ddth_Dao_DaoFactory($configFile);
            self::$cache[$configFile] = $obj;
        }
        return $obj;
    }

    /**
     * Initializes DAO factory
     *
     * @param string $configFile
     * @throws {@link Ddth_Dao_DaoException}
     */
    public function init($configFile = NULL) {
        if ( $this->initialized ) {
            return;
        }
        if ( $configFile === NULL ) {
            $this->init(self::DEFAULT_CONFIG_FILE);
        }
        $fileContent = Ddth_Commons_Loader::loadFileContent($configFile);
        if ( $fileContent === NULL ) {
            $msg = "Can not read file [$configFile]!";
            throw new Ddth_Dao_DaoException($msg);
        }
        $this->props = new Ddth_Commons_Properties();
        try {
            $this->props->import($fileContent);
        } catch ( Exception $e ) {
            $msg = $e->getMessage();
            throw new Ddth_Dao_DaoException($msg, $e->getCode());
        }
        $this->initialized = true;
    }

    /**
     * Gets a property.
     *
     * @param string $name
     * @return string
     */
    protected function getProperty($name) {
        return $this->props->getProperty($name);
    }

    /**
     * Gets a DAO by name.
     *
     * @param string $name
     * @return {@link Ddth_Dao_IBoManager IBoManager}
     * @throws {@link Ddth_Dao_DaoException DaoException}
     */
    public function getDao($name) {
        $className = $this->getProperty($name);
        if ( $className === NULL || trim($className) === '' ) {
            return NULL;
        }
        $dao = isset($this->daoCache[$name])?$this->daoCache[$name]:NULL;
        if ( !($dao instanceof Ddth_Dao_IDao) ) {
            $dao = NULL;
        }
        if ( $dao === NULL ) {
            $dao = new $className();
            if ( $dao instanceof Ddth_Dao_IDao ) {
                $dao->init($this);
            } else {
                $dao = NULL;
            }
        }
        return $dao;
    }

    /**
     * Convenience method to retrieve a DAO by name following the convention:
     * when method Ddth_Dao_DaoFactory::getXxxDao is called:
     * - getDao('xxxDao') is called, if NULL is returned:
     * - getDao('XxxDao') is called, if NULL is returned:
     * - getDao('xxx') is called, if NULL is returned:
     * - getDao('Xxx') is called
     */
    public function __call($name, $arguments = Array()) {
        $matches = Array();
        if ( preg_match('/get((\w+)Dao)/i', $name, $matches) ) {
            $dao = $this->getDao($matches[1], $arguments);
            if ( $dao === NULL ) {
                $dao = $this->getDao(ucfirst($matches[1]), $arguments);
            }
            if ( $dao === NULL ) {
                $dao = $this->getDao($matches[2], $arguments);
            }
            if ( $dao === NULL ) {
                $dao = $this->getDao(ucfirst($matches[2]), $arguments);
            }
            return $dao;
        }
        return NULL;
    }
}
?>