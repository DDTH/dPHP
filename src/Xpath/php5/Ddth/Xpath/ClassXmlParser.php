<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * XML to Xnode parser.
 *
 * LICENSE: This source file is subject to version 3.0 of the GNU Lesser General
 * Public License that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html. If you did not receive a copy of
 * the GNU Lesser General Public License and are unable to obtain it through the web,
 * please send a note to gnu@gnu.org, or send an email to any of the file's authors
 * so we can email you a copy.
 *
 * @package		Xpath
 * @author		Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version			$Id$
 * @since      	File available since v0.1
 */

/**
 * XML to Xnode parser.
 *
 * This class provides APIs to parse an XML document and convert it to a tree of
 * {@link Ddth_Xpath_Xnode Xnode}s.
 *
 * @package    	Xpath
 * @author     	Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright	2008 DDTH.ORG
 * @license    	http://www.gnu.org/licenses/lgpl.html  LGPL 3.0
 * @version    	0.1
 * @since      	Class available since v0.1
 */
abstract class Ddth_Xpath_XmlParser {
    private static $instance = NULL;

    /**
     * Gets an instance of Ddth_Xpath_XmlParser.
     *
     * @return Ddth_Xpath_XmlParser
     */
    public static function getInstance() {
        if ( self::$instance === NULL ) {
            self::$instance = new Ddth_Xpath_SimpleXml_XmlParser();
        }
        return self::$instance;
    }

    /**
     * Constructs a new Ddth_Xpath_XmlParser object.
     */
    protected function __construct() {
    }

    /**
     * Parses an XML document and returns the root node as a Xnode.
     *
     * @param string the XML document to parse
     * @return Xnode
     */
    public abstract function parseXml($xml);
}
?>