<?php
/**
 * Manage files for users
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  File
 * @author      marcan <marcan@impresscms.org>
 * @copyright   Copyright (c) 2007 The ImpressCMS Project <http://www.impresscms.org>
 * @version     SVN: $Id: Handler.php 12313 2013-09-15 21:14:35Z skenow $
 */

declare(strict_types=1);

namespace Icms\Data\File;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * File handler class.
 *
 * Provides data access mechanisms to Icms\Data\File\Entity (formerly icms_data_file_Object)
 * instances persisted via the Ipf handler.
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  File
 */
class Handler extends \Icms\Ipf\Handler
{
    /**
     * Constructor.
     */
    public function __construct($db)
    {
        parent::__construct($db, "data_file", "fileid", "caption", "desc", "icms");
    }
}

\class_alias(Handler::class, 'icms_data_file_Handler');
