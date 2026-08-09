<?php
declare(strict_types=1);

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

namespace Icms\Form\Elements\Select;

use Icms\Form\Elements\Select as SelectElement;

/**
 * Creates a form field for selecting a user group or groups
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id: Group.php 12313 2013-09-15 21:14:35Z skenow $
 */

if (!defined('ICMS_ROOT_PATH')) {
    die("ImpressCMS root path not defined");
}

/**
 * A field with a choice of available groups
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class Group extends SelectElement
{
	/**
	 * Constructor
	 *
	 * @param	string $caption
	 * @param	string $name
	 * @param=bool $include_anon Include group "anonymous"?
	 * @param	mixed $value Pre-selected value (or array of them).
	 * @param	int $size Number or rows. "1" makes a drop-down-list.
	 * @param=bool $multiple Allow multiple selections?
	 */
	public function __construct(string $caption, string $name, bool $include_anon = false, ?string $value = null, int $size = 1, bool $multiple = false) {
		parent::__construct($caption, $name, $value, $size, $multiple);
		$member_handler = \icms::handler('icms_member');
		if (!$include_anon) {
			$this->addOptionArray($member_handler->getGroupList(new \Icms\Db\Criteria\Item('groupid', ICMS_GROUP_ANONYMOUS, '!=')));
		} else {
			$this->addOptionArray($member_handler->getGroupList());
		}
	}
}

class_alias(Group::class, 'icms_form_elements_select_Group');