<?php
/**
 * About the ImpressCMS system
 *
 * @category	icms
 * @package		Administration
 * @subpackage	System
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 */

include_once "admin_header.php";
$aboutObj = new icms_ipf_About();
$aboutObj->render();
