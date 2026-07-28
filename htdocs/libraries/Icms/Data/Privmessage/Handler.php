<?php
/**
 * Manage private messages
 *
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @author      Kazumi Ono <onokazu@xoops.org>
 * @category    ICMS
 * @package     Data
 * @subpackage  Privmessage
 * @version     SVN: $Id:Handler.php 19775 2010-07-11 18:54:25Z malanciault $
 */

declare(strict_types=1);

namespace Icms\Data\Privmessage;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Private message handler class.
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  Privmessage
 */
class Handler extends \Icms\Core\EntityHandler
{
    /**
     * Create a new private message object.
     */
    public function &create($isNew = true)
    {
        $pm = new Entity();
        if ($isNew) {
            $pm->setNew();
        }
        return $pm;
    }

    /**
     * Load a private message object.
     */
    public function &get($id)
    {
        $pm = false;
        $id = (int) $id;
        if ($id > 0) {
            $sql = "SELECT * FROM " . $this->db->prefix('priv_msgs') . " WHERE msg_id='" . $id . "'";
            if (!$result = $this->db->query($sql)) {
                return $pm;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $pm = new Entity();
                $pm->assignVars($this->db->fetchArray($result));
            }
        }
        return $pm;
    }

    /**
     * Insert a message in the database.
     */
    public function insert($pm, $force = false): bool
    {
        if (!is_a($pm, Entity::class)) {
            return false;
        }
        if (!$pm->isDirty()) {
            return true;
        }
        if (!$pm->cleanVars()) {
            return false;
        }
        foreach ($pm->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($pm->isNew()) {
            $msg_id = $this->db->genId('priv_msgs_msg_id_seq');
            $sql = sprintf(
                "INSERT INTO %s (msg_id, msg_image, subject, from_userid, to_userid, msg_time, msg_text, read_msg)
                VALUES ('%u', %s, %s, '%u', '%u', '%u', %s, '%u')",
                $this->db->prefix('priv_msgs'),
                (int) $msg_id,
                $this->db->quoteString($msg_image),
                $this->db->quoteString($subject),
                (int) $from_userid,
                (int) $to_userid,
                time(),
                $this->db->quoteString($msg_text),
                0
            );
        } else {
            $sql = sprintf(
                "UPDATE %s SET msg_image = %s, subject = %s, from_userid = '%u', to_userid = '%u', msg_text = %s, read_msg = '%u' WHERE msg_id = '%u'",
                $this->db->prefix('priv_msgs'),
                $this->db->quoteString($msg_image),
                $this->db->quoteString($subject),
                (int) $from_userid,
                (int) $to_userid,
                $this->db->quoteString($msg_text),
                (int) $read_msg,
                (int) $msg_id
            );
        }
        $queryFunc = empty($force) ? "query" : "queryF";
        if (!$result = $this->db->{$queryFunc}($sql)) {
            return false;
        }
        if (empty($msg_id)) {
            $msg_id = $this->db->getInsertId();
        }
        $pm->assignVar('msg_id', (int) $msg_id);
        return true;
    }

    /**
     * Delete from the database.
     */
    public function delete($pm): bool
    {
        if (!is_a($pm, Entity::class)) {
            return false;
        }
        if (!$result = $this->db->query(sprintf("DELETE FROM %s WHERE msg_id = '%u'", $this->db->prefix('priv_msgs'), (int) $pm->getVar('msg_id')))) {
            return false;
        }
        return true;
    }

    /**
     * Load messages from the database.
     */
    public function getObjects($criteria = null, $id_as_key = false): array
    {
        $ret = [];
        $limit = $start = 0;
        $sql = 'SELECT * FROM ' . $this->db->prefix('priv_msgs');
        if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
            $sql .= ' ' . $criteria->renderWhere();
            $sort = !in_array($criteria->getSort(), ['msg_id', 'msg_time', 'from_userid']) ? 'msg_id' : $criteria->getSort();
            $sql .= ' ORDER BY ' . $sort . ' ' . $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $pm = new Entity();
            $pm->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $pm;
            } else {
                $ret[$myrow['msg_id']] =& $pm;
            }
            unset($pm);
        }
        return $ret;
    }

    /**
     * Count messages.
     */
    public function getCount($criteria = null): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('priv_msgs');
        if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return 0;
        }
        [$count] = $this->db->fetchRow($result);
        return (int) $count;
    }

    /**
     * Mark a message as read.
     */
    public function setRead($pm): bool
    {
        if (!is_a($pm, Entity::class)) {
            return false;
        }
        $sql = sprintf("UPDATE %s SET read_msg = '1' WHERE msg_id = '%u'", $this->db->prefix('priv_msgs'), (int) $pm->getVar('msg_id'));
        if (!$this->db->queryF($sql)) {
            return false;
        }
        return true;
    }
}

\class_alias(Handler::class, 'icms_data_privmessage_Handler');
