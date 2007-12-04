<?php
// $Id: handlerregistry.php 2 2005-11-02 18:23:29Z skalpa $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project (http://www.xoops.org/)                        //
// ------------------------------------------------------------------------- //

/**
 * A registry for holding references to {@link XoopsObjectHandler} classes
 * 
 * @package     kernel
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

class XoopsHandlerRegistry
{
    /**
     * holds references to handler class objects
     * 
     * @var     array
     * @access	private
     */
    var $_handlers = array();

	/**
	 * get a reference to the only instance of this class
     * 
     * if the class has not been instantiated yet, this will also take 
     * care of that
	 * 
     * @static
     * @staticvar   object  The only instance of this class
     * @return      object  Reference to the only instance of this class
	 */
    function &instance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new XoopsHandlerRegistry();
        }
        return $instance;
    }

    /**
     * Register a handler class object
     * 
     * @param	string  $name     Short name of a handler class
     * @param	object  &$handler {@link XoopsObjectHandler} class object
     */
    function setHandler($name, &$handler)
    {
        $this->_handlers['kernel'][$name] =& $handler;
    }

    /**
     * Get a registered handler class object
     * 
     * @param	string  $name     Short name of a handler class
     * 
     * @return	object {@link XoopsObjectHandler}, FALSE if not registered
     */
    function &getHandler($name)
    {
        if (!isset($this->_handlers['kernel'][$name])) {
            return false;
        }
        return $this->_handlers['kernel'][$name];
    }

    /**
     * Unregister a handler class object
     * 
     * @param	string  $name     Short name of a handler class
     */
    function unsetHandler($name)
    {
        unset($this->_handlers['kernel'][$name]);
    }

    /**
     * Register a handler class object for a module
     * 
     * @param	string  $module   Directory name of a module
     * @param	string  $name     Short name of a handler class
     * @param	object  &$handler {@link XoopsObjectHandler} class object
     */
    function setModuleHandler($module, $name, &$handler)
    {
        $this->_handlers['module'][$module][$name] =& $handler;
    }

    /**
     * Get a registered handler class object for a module
     * 
     * @param	string  $module   Directory name of a module
     * @param	string  $name     Short name of a handler class
     * 
     * @return	object {@link XoopsObjectHandler}, FALSE if not registered
     */
    function &getModuleHandler($module, $name)
    {
        if (!isset($this->_handlers['module'][$module][$name])) {
            return false;
        }
        return $this->_handlers['module'][$module][$name];
    }

    /**
     * Unregister a handler class object for a module
     * 
     * @param	string  $module   Directory name of a module
     * @param	string  $name     Short name of a handler class
     */
    function unsetModuleHandler($module, $name)
    {
        unset($this->_handlers['module'][$module][$name]);
    }

}
?>