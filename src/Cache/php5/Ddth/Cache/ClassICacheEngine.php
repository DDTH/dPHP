<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Representation of a cache engine.
 *
 * LICENSE: See the included license.txt file for detail.
 *
 * COPYRIGHT: See the included copyright.txt file for detail.
 *
 * @package     Cache
 * @author      Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version     $Id$
 * @since       File available since v0.2
 */

/**
 * Representation of a cache engine.
 *
 * @package     Cache
 * @author     	Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @since      	Class available since v0.2
 */
interface Ddth_Cache_ICacheEngine {
    /**
     * Removes all entries.
     */
    public function clear();

    /**
     * Clean-up method.
     */
    public function destroy();

    /**
     * Initializing method.
     *
     * @param Array $config cache configuration
     */
    public function init($config);

    /**
     * Checks if an entry exists.
     *
     * @param string $key
     * @return bool
     */
    public function exists($key);

    /**
     * Retrieves a cache entry.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Puts an entry into the cache.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed old entry with the same key (if exists)
     */
    public function put($key, $value);

    /**
     * Removes an entry.
     *
     * @param string $key
     * @return mixed existing entry associated with the key (if exists)
     */
    public function remove($key);
}
?>
