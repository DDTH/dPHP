<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Representation of a cache.
 *
 * LICENSE: See the included license.txt file for detail.
 *
 * COPYRIGHT: See the included copyright.txt file for detail.
 *
 * @package     Cache
 * @author      Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version     $Id$
 * @since       File available since v0.1
 */

/**
 * Representation of a cache.
 *
 * @package     Cache
 * @author     	Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @since      	Class available since v0.1
 */
interface Ddth_Cache_ICache {
    /**
     * Removes all entries from this cache.
     */
    public function clear();

    /**
     * Clean-up method.
     */
    public function destroy();

    /**
     * Initializing method.
     *
     * @param string $name cache's name
     * @param Array $config cache configuration
     * @param Ddth_Cache_CacheManager $manager
     */
    public function init($name, $config, $manager);

    /**
     * Checks if an entry exists in this cache.
     *
     * @param string $key
     * @return bool
     */
    public function exists($key);

    /**
     * Retrieves a cache entry from this cache.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Gets cache's current size (number of elements).
     *
     * @return int
     */
    public function getSize();

    /**
     * Gets this cache's associated cache manager.
     *
     * @return Ddth_Cache_CacheManager
     */
    public function getCacheManager();

    /**
     * Gets this cache's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Gets number of cache "hits".
     *
     * @return int
     */
    public function getNumHits();

    /**
     * Gets number of cache "misses".
     *
     * @return int
     */
    public function getNumMisses();

    /**
     * Puts an entry into this cache.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed old entry with the same key (if exists)
     */
    public function put($key, $value);

    /**
     * Removes an entry from this cache.
     *
     * @param string $key
     * @return mixed existing entry associated with the key (if exists)
     */
    public function remove($key);
}
?>
