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
 * Creates form attribute which shows match possibilities for search form
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id: Matchoption.php 12313 2013-09-15 21:14:35Z skenow $
 */

if (!defined('ICMS_ROOT_PATH')) {
    die("ImpressCMS root path not defined");
}

/**
 * A selection box with options for matching search terms.
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class Matchoption extends SelectElement
{
	/**
	 * Constructor
	 *
	 * @param	string $caption
	 * @param string $name
	 * @param	mixed $value Pre-selected value (or array of them).
	 *                            Legal values are {@link XOOPS_MATCH_START}, {@link XOOPS_MATCH_END},
	 *                            {@link XOOPS_MATCH_EQUAL}, and {@link XOOPS_MATCH_CONTAIN}
	 * @param	int $size Number of rows. "1" makes a drop-down-list
	 */
	public function __construct(string $caption, string $name, ?string $value = null, int $size = 1) {
		parent::__construct($caption, $name, $value, $size, false);
		$this->addOption(XOOPS_MATCH_START, _STARTSWITH);
		$this->addOption(XOOPS_MATCH_END, _ENDSWITH);
		$this->addOption(XOOPS_MATCH_EQUAL, _MATCHES);
		$this->addOption(XOOPS_MATCH_CONTAIN, _CONTAINS);
	}
}

\class_alias(Matchoption::class, 'icms_form_elements_select_Matchoption');