<?php
/**
 * Manage files for users
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  File
 * @author      marcan <marcan@impresscms.org>
 * @copyright   Copyright (c) 2007 The ImpressCMS Project <http://www.impresscms.org>
 * @version     SVN: $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
 */

declare(strict_types=1);

namespace Icms\Data\File;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * File entity (formerly icms_data_file_Object).
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  File
 */
class Entity extends \Icms\Ipf\Entity
{
    /**
     * Constructor.
     */
    public function __construct(&$handler)
    {
        parent::__construct($handler);
        $this->quickInitVar('fileid', XOBJ_DTYPE_INT, true);
        $this->quickInitVar('caption', XOBJ_DTYPE_TXTBOX);
        $this->quickInitVar('description', XOBJ_DTYPE_TXTAREA);
        $this->quickInitVar('url', XOBJ_DTYPE_TXTBOX);
        $this->quickInitVar('item_id', XOBJ_DTYPE_INT);
        $this->quickInitVar('module_id', XOBJ_DTYPE_INT);
        $this->quickInitVar('date', XOBJ_DTYPE_LTIME);
        $this->quickInitVar('uid', XOBJ_DTYPE_INT);

        $this->setControl('description', 'dhtmltextarea');
        $this->hideFieldFromForm(['item_id', 'module_id', 'date', 'uid']);
    }
}

\class_alias(Entity::class, 'icms_data_file_Object');
