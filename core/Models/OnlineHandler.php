<?php


namespace ImpressCMS\Core\Models;


use Exception;
use icms;
use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaElement;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use PDO;

/**
 * Handles online data
 *
 * @package ImpressCMS\Core\Models
 */
class OnlineHandler extends AbstractExtendedHandler
{

	/**
	 * @inheritDoc
	 */
	public function __construct(&$db)
	{
		parent::__construct(
			$db,
			'online',
			'online_uid',
			'online_uid',
			'online_uname',
			null,
			'online'
		);
	}

	/**
	 * Gets all online information
	 *
	 * @param CriteriaElement|null $criteria Criteria
	 *
	 * @return Online[]
	 */
	public function getAll($criteria = null) {
		return $this->getObjects($criteria, false, false);
	}

	/**
	 * Delete all expired online information
	 *
	 * @param	int $expire Expiration time in seconds
	 */
	public function gc($expire) {
		$this->deleteAll(
			new CriteriaItem('online_updated', time() - (int) ($expire), '<')
		);
	}

	/**
	 * Delete online data for a user
	 *
	 * @param int $userId User ID
	 *
	 * @return    bool
	 */
	public function destroy($userId) {
		return $this->deleteAll(
			new CriteriaItem('online_uid', $userId)
		);
	}

	/**
	 * Updates online information into the database
	 *
	 * @param int $userId UID of the active user
	 * @param string $userName Username
	 * @param int $time Time
	 * @param int $moduleId Current module
	 * @param string $ip User's IP adress
	 *
	 * @return    bool
	 * @throws Exception
	 */
	public function write($userId, $userName, $time, $moduleId, $ip) {

		$criteria = new CriteriaCompo();
		$criteria->add(new CriteriaItem('online_uid', $userId = (int)$userId));
		if (!$userId) {
			$criteria->add(new CriteriaItem('online_ip', $ip));
		}

		/**
		 * @var Online $item
		 */
		$items = $this->getObjects($criteria, false, true);
		if (empty($items)) {
			$item = $this->create();
			$item->online_ip = $ip;
			$item->online_uid = $userId;
			$item->online_uname = $userName;
			$item->online_updated = $time;
		} else {
			$item = $items[0];
		}
		$item->online_time = $time;
		$item->online_module = $moduleId;

		return $item->store();
	}

	/**
	 * Get online items for list
	 *
	 * @param int $from From where to get
	 * @param int $limit How many to get
	 *
	 * @return array
	 */
	public function getItemsForList(int $from, int $limit = 20): array
	{
		/**
		 * @var UserHandler $userHandler
		 */
		$userHandler = icms::handler('icms_member_user');

		/**
		 * @var ModuleHandler $moduleHandler
		 */
		$moduleHandler = icms::handler('icms_module');

		$result = $this->db->perform('SELECT u.uid uid, u.user_avatar avatar, o.online_ip ip, o.online_updated, m.name module, u.uname
 				 FROM ' . $this->table . ' o
				 LEFT JOIN ' . $userHandler->table . ' u ON o.online_uid = u.uid AND o.online_uid != 0
				 LEFT JOIN ' . $moduleHandler->table . ' m ON o.online_module = m.mid AND o.online_module != 0 AND m.isactive = 1
				 LIMIT ' . (int)$from . ', ' . (int)$limit);

		if (!$result) {
			return [];
		}

		return $result->fetchAll(PDO::FETCH_ASSOC);
	}

}