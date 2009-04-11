<?php
// $Id$
// database.php - defines abstract database wrapper class
/**
* Database Base Class
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	database
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id$
*/

/**
 * @package database
 * @subpackage  main
 * @since XOOPS
 * @version $Id$
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

/**
 * make sure this is only included once!
 */
if ( !defined("XOOPS_C_DATABASE_INCLUDED") ) {
	define("XOOPS_C_DATABASE_INCLUDED",1);

  /**
   * Abstract base class for Database access classes
   *
   * @abstract
   *
   * @package database
   * @subpackage  main
   * @since XOOPS
   *
   * @author      Kazumi Ono  <onokazu@xoops.org>
   * @copyright   copyright (c) 2000-2003 XOOPS.org
   * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
   */
  class XoopsDatabase
  {
    /**
     * Prefix for tables in the database
     * @var string
     */
    var $prefix = '';
    /**
     * reference to a {@link XoopsLogger} object
           * @see XoopsLogger
     * @var object XoopsLogger
     */
    var $logger;
    
    	/**
    	 * If statements that modify the database are selected
    	 * @var boolean
    	 */
  	var $allowWebChanges = false;
    
    /**
     * constructor
       *
       * will always fail, because this is an abstract class!
     */
    function XoopsDatabase()
    {
    	// exit("Cannot instantiate this class directly");
    }
  
    /**
     * assign a {@link XoopsLogger} object to the database
     *
     * @see XoopsLogger
     * @param object $logger reference to a {@link XoopsLogger} object
     */
    function setLogger(&$logger)
    {
    	$this->logger =& $logger;
    }
  
    /**
     * set the prefix for tables in the database
     *
     * @param string $value table prefix
     */
    function setPrefix($value)
    {
    	$this->prefix = $value;
    }

		/**
		 * attach the prefix.'_' to a given tablename
     *
     * if tablename is empty, only prefix will be returned
		 *
     * @param string $tablename tablename
     * @return string prefixed tablename, just prefix if tablename is empty
		 */
		function prefix($tablename='')
		{
			if ( $tablename != '' ) {
				return $this->prefix .'_'. $tablename;
			} else {
				return $this->prefix;
			}
		}
	} // DataBase class


} // If database_included constant


/**
 * Only for backward compatibility
 *
 * @package database
 * @subpackage  main
 * @since XOOPS
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @deprecated
 */
class Database
{
	function &getInstance() {
		$inst =& XoopsDatabaseFactory::getDatabaseConnection();
		return $inst;
	}
}

?>