<?php
/**
 * Core class for managing comments
 *
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author      Kazumi Ono   <onokazu@xoops.org>
 * @copyright   Copyright (c) 2000 XOOPS.org
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @category    ICMS
 * @package     Data
 * @subpackage  Comment
 * @version     SVN: $Id:Handler.php 19775 2010-07-11 18:54:25Z malanciault $
 */

declare(strict_types=1);

namespace Icms\Data\Comment;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Comment handler class.
 *
 * Provides data access mechanisms to the data source of Icms\Data\Comment\Entity
 * (formerly icms_data_comment_Object) instances.
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  Comment
 */
class Handler extends \Icms\Core\ObjectHandler
{
    /**
     * Create a new comment entity.
     */
    public function &create($isNew = true)
    {
        $comment = new Entity();
        if ($isNew) {
            $comment->setNew();
        }
        return $comment;
    }

    /**
     * Retrieve a comment by id.
     *
     * @return mixed
     */
    public function &get($id)
    {
        $comment = false;
        $id = (int) $id;
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('xoopscomments')
                . " WHERE com_id='" . $id . "'";
            if (!$result = $this->db->query($sql)) {
                return $comment;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $comment = new Entity();
                $comment->assignVars($this->db->fetchArray($result));
            }
        }
        return $comment;
    }

    /**
     * Insert a comment into the database.
     */
    public function insert(&$comment): bool
    {
        if (!is_a($comment, Entity::class)) {
            return false;
        }
        if (!$comment->isDirty()) {
            return true;
        }
        if (!$comment->cleanVars()) {
            return false;
        }
        foreach ($comment->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($comment->isNew()) {
            $com_id = $this->db->genId('xoopscomments_com_id_seq');
            $sql = sprintf(
                "INSERT INTO %s (
                com_id, com_pid, com_modid, com_icon, com_title, com_text,
                com_created, com_modified, com_uid, com_ip, com_sig,
                com_itemid, com_rootid, com_status, com_exparams,
                dohtml, dosmiley, doxcode, doimage, dobr)
                VALUES ('%u', '%u', '%u', %s, %s, %s, '%u', '%u', '%u', %s, '%u', '%u', '%u', '%u', %s, '%u', '%u', '%u', '%u', '%u')",
                $this->db->prefix('xoopscomments'),
                (int) $com_id,
                (int) $com_pid,
                (int) $com_modid,
                $this->db->quoteString($com_icon),
                $this->db->quoteString($com_title),
                $this->db->quoteString($com_text),
                (int) $com_created,
                (int) $com_modified,
                (int) $com_uid,
                $this->db->quoteString($com_ip),
                (int) $com_sig,
                (int) $com_itemid,
                (int) $com_rootid,
                (int) $com_status,
                $this->db->quoteString($com_exparams),
                (int) $dohtml,
                (int) $dosmiley,
                (int) $doxcode,
                (int) $doimage,
                (int) $dobr
            );
        } else {
            $sql = sprintf(
                "UPDATE %s SET com_pid = '%u',
                com_icon = %s, com_title = %s, com_text = %s,
                com_created = '%u', com_modified = '%u', com_uid = '%u',
                com_ip = %s, com_sig = '%u', com_itemid = '%u',
                com_rootid = '%u', com_status = '%u', com_exparams = %s,
                dohtml = '%u', dosmiley = '%u', doxcode = '%u', doimage = '%u', dobr = '%u'
                WHERE com_id = '%u'",
                $this->db->prefix('xoopscomments'),
                (int) $com_pid,
                $this->db->quoteString($com_icon),
                $this->db->quoteString($com_title),
                $this->db->quoteString($com_text),
                (int) $com_created,
                (int) $com_modified,
                (int) $com_uid,
                $this->db->quoteString($com_ip),
                (int) $com_sig,
                (int) $com_itemid,
                (int) $com_rootid,
                (int) $com_status,
                $this->db->quoteString($com_exparams),
                (int) $dohtml,
                (int) $dosmiley,
                (int) $doxcode,
                (int) $doimage,
                (int) $dobr,
                (int) $com_id
            );
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($com_id)) {
            $com_id = $this->db->getInsertId();
        }
        $comment->assignVar('com_id', (int) $com_id);
        return true;
    }

    /**
     * Delete a comment from the database.
     */
    public function delete(&$comment): bool
    {
        if (!is_a($comment, Entity::class)) {
            return false;
        }
        $sql = sprintf(
            "DELETE FROM %s WHERE com_id = '%u'",
            $this->db->prefix('xoopscomments'),
            (int) $comment->getVar('com_id')
        );
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Get some comments.
     */
    public function getObjects($criteria = null, $id_as_key = false): array
    {
        $ret = [];
        $limit = $start = 0;
        $sql = 'SELECT * FROM ' . $this->db->prefix('xoopscomments');
        if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
            $sql .= ' ' . $criteria->renderWhere();
            $sort = ($criteria->getSort() != '') ? $criteria->getSort() : 'com_id';
            $sql .= ' ORDER BY ' . $sort . ' ' . $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $comment = new Entity();
            $comment->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $comment;
            } else {
                $ret[$myrow['com_id']] =& $comment;
            }
            unset($comment);
        }
        return $ret;
    }

    /**
     * Count comments matching the criteria.
     */
    public function getCount($criteria = null): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('xoopscomments');
        if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result =& $this->db->query($sql)) {
            return 0;
        }
        [$count] = $this->db->fetchRow($result);
        return (int) $count;
    }

    /**
     * Delete multiple comments.
     */
    public function deleteAll($criteria = null): bool
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('xoopscomments');
        if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Get a list of comments.
     */
    public function getList($criteria = null): array
    {
        $comments = $this->getObjects($criteria, true);
        $ret = [];
        foreach (array_keys($comments) as $i) {
            $ret[$i] = $comments[$i]->getVar('com_title');
        }
        return $ret;
    }

    /**
     * Retrieves comments for an item.
     */
    public function getByItemId($module_id, $item_id, $order = null, $status = null, $limit = null, $start = 0): array
    {
        $criteria = new \icms_db_criteria_Compo(new \icms_db_criteria_Item('com_modid', (int) $module_id));
        $criteria->add(new \icms_db_criteria_Item('com_itemid', (int) $item_id));
        if (isset($status)) {
            $criteria->add(new \icms_db_criteria_Item('com_status', (int) $status));
        }
        if (isset($order)) {
            $criteria->setOrder($order);
        }
        if (isset($limit)) {
            $criteria->setLimit($limit);
            $criteria->setStart($start);
        }
        return $this->getObjects($criteria);
    }

    /**
     * Gets total number of comments for an item.
     */
    public function getCountByItemId($module_id, $item_id, $status = null): int
    {
        $criteria = new \icms_db_criteria_Compo(new \icms_db_criteria_Item('com_modid', (int) $module_id));
        $criteria->add(new \icms_db_criteria_Item('com_itemid', (int) $item_id));
        if (isset($status)) {
            $criteria->add(new \icms_db_criteria_Item('com_status', (int) $status));
        }
        return $this->getCount($criteria);
    }

    /**
     * Get the top-level comments.
     */
    public function getTopComments($module_id, $item_id, $order, $status = null): array
    {
        $criteria = new \icms_db_criteria_Compo(new \icms_db_criteria_Item('com_modid', (int) $module_id));
        $criteria->add(new \icms_db_criteria_Item('com_itemid', (int) $item_id));
        $criteria->add(new \icms_db_criteria_Item('com_pid', 0));
        if (isset($status)) {
            $criteria->add(new \icms_db_criteria_Item('com_status', (int) $status));
        }
        $criteria->setOrder($order);
        return $this->getObjects($criteria);
    }

    /**
     * Retrieve a whole thread.
     */
    public function getThread($comment_rootid, $comment_id, $status = null): array
    {
        $criteria = new \icms_db_criteria_Compo(new \icms_db_criteria_Item('com_rootid', (int) $comment_rootid));
        $criteria->add(new \icms_db_criteria_Item('com_id', (int) $comment_id, '>='));
        if (isset($status)) {
            $criteria->add(new \icms_db_criteria_Item('com_status', (int) $status));
        }
        return $this->getObjects($criteria);
    }

    /**
     * Update a single field on a comment.
     */
    public function updateByField(&$comment, $field_name, $field_value): bool
    {
        $comment->unsetNew();
        $comment->setVar($field_name, $field_value);
        return $this->insert($comment);
    }

    /**
     * Delete all comments for one whole module.
     */
    public function deleteByModule($module_id): bool
    {
        return $this->deleteAll(new \icms_db_criteria_Item('com_modid', (int) $module_id));
    }
}

\class_alias(Handler::class, 'icms_data_comment_Handler');
