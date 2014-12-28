<?php
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
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Input validation and processing, BB code conversion, Smiley conversion
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @subpackage	Textsanitizer
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		SVN: $Id$
 */

/**
 * Class to "clean up" text for various uses
 *
 * <b>Singleton</b>
 *
 * @category	ICMS
 * @package		Core
 * @subpackage	Textsanitizer
 *
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @author		Goghs Cheng
 */
class icms_core_Textsanitizer {

	/**
	 * @public	array
	 */
	public $displaySmileys = array();

	/**
	 * @public	array
	 */
	public $allSmileys = array();

	/**
	 *
	 */
	public $censorConf;

	/**
	 * Constructor of this class
	 * Gets allowed html tags from admin config settings
	 * <br> should not be allowed since nl2br will be used
	 * when storing data.
	 *
	 * @todo So far, this does nuttin' ;-)
	 */
	public function __construct() {}

	/**
	 * Access the only instance of this class
	 *
	 * @return   object
	 *
	 * @static
	 * @staticvar   object
	 */
	static public function &getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_core_Textsanitizer();
		}
		return $instance;
	}

	/**
	 * Filters out invalid strings included in URL, if any
	 *
	 * @param   array  $matches
	 * @return  string
	 */
	public function _filterImgUrl($matches) {
		if ($this->checkUrlString($matches[2])) {
			return $matches[0];
		} else {
			return '';
		}
	}

	/**
	 * Checks if invalid strings are included in URL
	 *
	 * @param   string  $text
	 * @return  bool
	 */
	public function checkUrlString($text) {
		// Check control code
		if (preg_match("/[\0-\31]/", $text)) {
			return false;
		}
		// check black pattern(deprecated)
		return !preg_match("/^(javascript|vbscript|about):/i", $text);
	}

	/**
	 * Filters textarea form data in DB for display
	 *
	 * @param   string  $text
	 * @param   bool	$html   allow html?
	 * @param   bool	$smiley allow smileys?
	 * @param   bool	$xcode  allow xoopscode?
	 * @param   bool	$image  allow inline images?
	 * @param   bool	$br	 convert linebreaks?
	 * @return  string
	 */
	public function displayTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1) {
		// Before this can be deprecated, the events for displayTarea need to be added, first
		//icms_core_Debug::setDeprecated('icms_core_DataFilter::checkVar - type = text or html, $options1 = input or output', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));

		/* trigger all the events tied to the beforeDisplayTarea event */
		icms::$preload->triggerEvent('beforeDisplayTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		if ($html = 0){
			$text = icms_core_DataFilter::filterTextareaDisplay($text, $smiley, $xcode, $image, $br);
		} else {
			$text = icms_core_DataFilter::filterHTMLdisplay($text, $xcode, $br);
		}

		/* trigger all events tied to the afterDisplayTarea event */
		icms::$preload->triggerEvent('afterDisplayTarea', array(&$text, $html, $smiley, $xcode, $image, $br));
		return $text;
	}

	/**
	 * Filters textarea form data submitted for preview
	 *
	 * The only difference between this and displayTarea is the need to deal with $_POST input instead of database output
	 *
	 * @param   string  $text
	 * @param   bool	$html   allow html?
	 * @param   bool	$smiley allow smileys?
	 * @param   bool	$xcode  allow xoopscode?
	 * @param   bool	$image  allow inline images?
	 * @param   bool	$br	 convert linebreaks?
	 * @return  string
	 */
	public function previewTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1) {
		 /* @deprecated Use icms_core_DataFilter::checkVar, instead - the events for previewTarea need to be added, first */
		//icms_core_Debug::setDeprecated('icms_core_DataFilter::checkVar - type = text or html, $options1 = input', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));

		/* trigger all the events tied to the beforePreviewTarea event */
		icms::$preload->triggerEvent('beforePreviewTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		$text = icms_core_DataFilter::stripSlashesGPC($text);

		if ($html = 0) {
			$text = icms_core_DataFilter::filterTextareaDisplay($text, $smiley, $xcode, $image, $br);
		} else {
			$text = icms_core_DataFilter::filterHTMLdisplay($text, $xcode, $br);
		}

		/* trigger all the events tied to the afterPreviewTarea event */
		icms::$preload->triggerEvent('afterPreviewTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		return $text;
	}
}
