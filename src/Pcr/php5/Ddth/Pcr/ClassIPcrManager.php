<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * PCR manager.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Pcr
 * @author		Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version			$Id$
 * @since      	File available since v0.1
 */

/**
 * PCR manager.
 *
 * @package    	Pcr
 * @author     	Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
interface Ddth_Pcr_IPcrManager {
    /**
     * Gets a node by id
     * 
     * @param int $id
     * @return Ddth_Pcr_INode
     */
    public function getNodeById($id);
}
?>