<?php
/**
 * Manage avatars for users
 *
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     LICENSE.txt
 * @category    ICMS
 * @package     Data
 * @subpackage  Avatar
 * @author      Kazumi Ono (aka onokazo)
 * @version     SVN: $Id:Handler.php 19775 2010-07-11 18:54:25Z malanciault $
 */

declare(strict_types=1);

namespace Icms\Data\Avatar;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Avatar handler class.
 *
 * Provides data access mechanisms to the data source of Icms\Data\Avatar\Entity
 * (formerly icms_data_avatar_Object) instances.
 *
 * @category    ICMS
 * @package     Data
 * @subpackage  Avatar
 */
class Handler extends \Icms\Core\ObjectHandler
{
    /**
     * Creates a new avatar object.
     */
    public function &create($isNew = true)
    {
        $avatar = new Entity();
        if ($isNew) {
            $avatar->setNew();
        }
        return $avatar;
    }

    /**
     * Gets an avatar object.
     *
     * @return mixed
     */
    public function &get($id)
    {
        $avatar = false;
        $id = (int) $id;
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('avatar')
                . " WHERE avatar_id='" . $id . "'";
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $avatar = new Entity();
                $avatar->assignVars($this->db->fetchArray($result));
                return $avatar;
            }
        }
        return $avatar;
    }

    /**
     * Inserts an avatar or updates an existing avatar.
     */
    public function insert(&$avatar): bool
    {
        if (!is_a($avatar, Entity::class)) {
            return false;
        }
        if (!$avatar->isDirty()) {
            return true;
        }
        if (!$avatar->cleanVars()) {
            return false;
        }
        foreach ($avatar->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($avatar->isNew()) {
            $avatar_id = $this->db->genId('avatar_avatar_id_seq');
            $sql = sprintf(
                "INSERT INTO %s (avatar_id, avatar_file, avatar_name, avatar_created, avatar_mimetype, avatar_display, avatar_weight, avatar_type)
                VALUES ('%u', %s, %s, '%u', %s, '%u', '%u', %s)",
                $this->db->prefix('avatar'),
                (int) $avatar_id,
                $this->db->quoteString($avatar_file),
                $this->db->quoteString($avatar_name),
                time(),
                $this->db->quoteString($avatar_mimetype),
                (int) $avatar_display,
                (int) $avatar_weight,
                $this->db->quoteString($avatar_type)
            );
        } else {
            $sql = sprintf(
                "UPDATE %s SET
                avatar_file = %s,
                avatar_name = %s,
                avatar_created = '%u',
                avatar_mimetype= %s,
                avatar_display = '%u',
                avatar_weight = '%u',
                avatar_type = %s
                WHERE avatar_id = '%u'",
                $this->db->prefix('avatar'),
                $this->db->quoteString($avatar_file),
                $this->db->quoteString($avatar_name),
                (int) $avatar_created,
                $this->db->quoteString($avatar_mimetype),
                (int) $avatar_display,
                (int) $avatar_weight,
                $this->db->quoteString($avatar_type),
                (int) $avatar_id
            );
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($avatar_id)) {
            $avatar_id = $this->db->getInsertId();
        }
        $avatar->assignVar('avatar_id', $avatar_id);
        return true;
    }

    /**
     * Deletes an avatar.
     */
    public function delete(&$avatar): bool
    {
        if (!is_a($avatar, Entity::class)) {
            return false;
        }

        $id = (int) $avatar->getVar('avatar_id');
        $sql = sprintf(
            "DELETE FROM %s WHERE avatar_id = '%u'",
            $this->db->prefix('avatar'),
            $id
        );
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $sql = sprintf(
            "DELETE FROM %s WHERE avatar_id = '%u'",
            $this->db->prefix('avatar_user_link'),
            $id
        );
        $this->db->query($sql);
        return true;
    }

    /**
     * @param object|null $criteria
     * @return array
     */
    public function &getObjects($criteria = null, $id_as_key = false)
    {
        $ret = [];
        $limit = $start = 0;
        $sql = 'SELECT a.*, COUNT(u.user_id) AS count FROM '
            . $this->db->prefix('avatar') . ' a LEFT JOIN '
            . $this->db->prefix('avatar_user_link') . ' u ON u.avatar_id=a.avatar_id';
        if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
            $sql .= ' ' . $criteria->renderWhere();
            $sql .= ' GROUP BY a.avatar_id ORDER BY avatar_weight, avatar_id';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $avatar = new Entity();
            $avatar->assignVars($myrow);
            $avatar->setUserCount((int) $myrow['count']);
            if (!$id_as_key) {
                $ret[] =& $avatar;
            } else {
                $ret[$myrow['avatar_id']] =& $avatar;
            }
            unset($avatar);
        }
        return $ret;
    }

    /**
     * Get a count of avatars meeting criteria.
     */
    public function getCount($criteria = null): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('avatar');
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
     * Links a user with an avatar.
     */
    public function addUser(int $avatar_id, int $user_id): bool
    {
        if ($avatar_id < 1 || $user_id < 1) {
            return false;
        }
        $sql = sprintf(
            "DELETE FROM %s WHERE user_id = '%u'",
            $this->db->prefix('avatar_user_link'),
            $user_id
        );
        $this->db->query($sql);
        $sql = sprintf(
            "INSERT INTO %s (avatar_id, user_id) VALUES ('%u', '%u')",
            $this->db->prefix('avatar_user_link'),
            $avatar_id,
            $user_id
        );
        if (!$result =& $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Get an array of users linked to an avatar.
     *
     * @return array|false
     */
    public function getUser(&$avatar)
    {
        $ret = [];

        if (!is_a($avatar, Entity::class)) {
            return false;
        }

        $sql = 'SELECT user_id FROM ' . $this->db->prefix('avatar_user_link')
            . " WHERE avatar_id='" . (int) $avatar->getVar('avatar_id') . "'";
        if (!$result = $this->db->query($sql)) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] =& $myrow['user_id'];
        }
        return $ret;
    }

    /**
     * Get a list of avatars.
     */
    public function getList($avatar_type = null, $avatar_display = null): array
    {
        $criteria = new \icms_db_criteria_Compo();
        if (isset($avatar_type)) {
            $avatar_type = ($avatar_type == 'C') ? 'C' : 'S';
            $criteria->add(new \icms_db_criteria_Item('avatar_type', $avatar_type));
        }
        if (isset($avatar_display)) {
            $criteria->add(new \icms_db_criteria_Item('avatar_display', (int) $avatar_display));
        }
        $avatars =& $this->getObjects($criteria, true);
        $ret = ['blank.gif' => _NONE];
        foreach (array_keys($avatars) as $i) {
            $ret[$avatars[$i]->getVar('avatar_file')] = $avatars[$i]->getVar('avatar_name');
        }
        return $ret;
    }

    /**
     * Gets list of avatar file names in a certain directory.
     */
    public static function getListFromDir(string $avatar_dir = ''): array
    {
        if ($avatar_dir !== '') {
            return \Icms\Core\Filesystem::getFileList(
                ICMS_ROOT_PATH . '/images/avatar/' . $avatar_dir . '/',
                $avatar_dir . '/',
                ['gif', 'jpg', 'png']
            );
        }
        return \Icms\Core\Filesystem::getFileList(
            ICMS_ROOT_PATH . '/images/avatar/',
            '',
            ['gif', 'jpg', 'png']
        );
    }

    /**
     * Gets list of all avatar image files inside default avatars directory.
     *
     * @return array|false
     */
    public static function getAllFromDir()
    {
        $avatars = [];
        $dirlist = \Icms\Core\Filesystem::getDirList(ICMS_ROOT_PATH . '/images/avatar/');
        if (count($dirlist) > 0) {
            foreach ($dirlist as $dir) {
                $avatars[$dir] =& \Icms\Core\Filesystem::getFileList(
                    ICMS_ROOT_PATH . '/images/avatar/' . $dir . '/',
                    $dir . '/',
                    ['gif', 'jpg', 'png']
                );
            }
        } else {
            return false;
        }
        return $avatars;
    }
}

\class_alias(Handler::class, 'icms_data_avatar_Handler');
