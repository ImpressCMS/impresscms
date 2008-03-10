<?php
/** ---------------------------------------------------------------------------
*  $Id: sdata.dist.php 356 2007-12-24 19:08:37Z malanciault $
*  ---------------------------------------------------------------------------
*
*  Project: ImpressCMS <http://www.impresscms.org/>
*
*  ImpressCMS is derived from XOOPS 2.0.17.1
*  <http://www.xoops.org/> Copyright (c) 2000-2007 XOOPS.org
*  Subsequent changes and additions: Copyright (c) 2007 ImpressCMS
*
*  ---------------------------------------------------------------------------
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  You may not change or alter any portion of this comment or credits
*  of supporting developers from this source code or any supporting
*  source code which is considered copyrighted (c) material of the
*  original comment or credit authors.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*  ---------------------------------------------------------------------------
*/

// Database Hostname
// Hostname of the database server. If you are unsure, 'localhost' works in most cases.
define('SDATA_DB_HOST', '');

// Database Username
// Your database user account on the host
define('SDATA_DB_USER', '');

// Database Password
// Password for your database user account
define('SDATA_DB_PASS', '');

// Database Name
// The name of database on the host. The installer will attempt to create the database if not exist
define('SDATA_DB_NAME', '');

// Main Password Salt Key
// The unique key used in the password algorhythm. Do NOT change this key once your site is Live, changing this key will render all passwords invalid & prevent users logging in.
define('SDATA_DB_SALT', '');

?>