<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Alternative PHP Cache (APC) cache engine.
 *
 * LICENSE: See the included license.txt file for detail.
 *
 * COPYRIGHT: See the included copyright.txt file for detail.
 *
 * @package     Cache
 * @subpackage  Engine
 * @author      Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version     $Id$
 * @since       File available since v0.2
 */

/**
 * Alternative PHP Cache (APC) cache engine.
 *
 * This cache engine utilizes php-apc APIs to store entries.
 *
 * @package     Cache
 * @subpackage  Engine
 * @author     	Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @since      	Class available since v0.2
 */
class Ddth_Cache_Engine_ApcEngine extends Ddth_Cache_Engine_AbstractEngine {

    /**
     * @see Ddth_Cache_ICacheEngine::clear()
     */
    public function clear() {
        return apc_clear_cache('user');
    }

    /**
     * @see Ddth_Cache_ICacheEngine::destroy()
     */
    public function destroy() {
        //EMPTY
    }

    /**
     * @see Ddth_Cache_ICacheEngine::init()
     */
    public function init($cache, $config) {
        if (!function_exists('apc_store')) {
            $msg = 'APC is not available!';
            throw new Ddth_Cache_CacheException($msg);
        }
        parent::init($cache, $config);
    }

    /**
     * @see Ddth_Cache_ICacheEngine::exists()
     */
    public function exists($key) {
        $key = $this->getCacheKeyPrefix() . $key;
        return apc_exists($key);
    }

    /**
     * This function assumes the retrieved value is in serialized form. Hence it
     * unserializes the value before returning it.
     *
     * @see Ddth_Cache_ICacheEngine::get()
     */
    public function get($key) {
        $key = $this->getCacheKeyPrefix() . $key;
        $result = apc_fetch($key, $success);
        return $success ? unserialize($result) : NULL;
    }

    /**
     * This function serializes the cache value before putting into cache.
     *
     * @see Ddth_Cache_ICacheEngine::put()
     */
    public function put($key, $value) {
        $key = $this->getCacheKeyPrefix() . $key;
        $result = apc_fetch($key, $success);
        $value = serialize($value);
        apc_store($key, $value);
        return $success ? $result : NULL;
    }

    /**
     * @see Ddth_Cache_ICacheEngine::remove()
     */
    public function remove($key) {
        $key = $this->getCacheKeyPrefix() . $key;
        $result = apc_fetch($key, $success);
        if ($success) {
            apc_delete($key);
        }
        return $success ? $result : NULL;
    }

    /**
     * @see Ddth_Cache_ICacheEngine::getNumHits()
     */
    public function getNumHits() {
        $result = apc_cache_info(FALSE, TRUE);
        return $result !== FALSE ? $result['num_hits'] : -1;
    }

    /**
     * @see Ddth_Cache_ICacheEngine::getNumMisses()
     */
    public function getNumMisses() {
        $result = apc_cache_info(FALSE, TRUE);
        return $result !== FALSE ? $result['num_misses'] : -1;
    }

    /**
     * @see Ddth_Cache_ICacheEngine::getSize()
     */
    public function getSize() {
        $result = apc_cache_info('user', TRUE);
        return $result !== FALSE ? $result['num_entries'] : -1;
    }
}
?>
