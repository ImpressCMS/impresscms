<?php
/**
 * About the ImpressCMS system
 * 
 * @category	icms
 * @package		Administration
 * @subpackage	System
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @version		$Id: about.php 11610 2012-02-28 03:53:55Z skenow $
 */

include_once "admin_header.php";
$aboutObj = new icms_ipf_About();
$aboutObj->render();
