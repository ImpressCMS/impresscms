<?php


namespace ImpressCMS\Modules\System\Extensions\WaitingInfoGetter;

use ImpressCMS\Modules\System\Extensions\WaitingInfoGetterInterface;

/**
 * Gets info about in activate users
 *
 * @package ImpressCMS\Modules\System\Extensions\WaitingInfoGetter
 */
class InactiveUsersWaitingInfoGetter implements WaitingInfoGetterInterface
{
	/**
	 * @var \icms_db_Connection
	 */
	private $db;

	/**
	 * Constructor.
	 *
	 * @param \icms_db_Connection $db
	 */
	public function __construct(\icms_db_Connection $db)
	{
		$this->db = $db;
	}

    /**
     * @inheritDoc
     */
    public function getLinkUrl(): string
    {
        return ICMS_URL . '/modules/system/admin.php?fct=findusers';
    }

    /**
     * @inheritDoc
     */
    public function getLinkTitle(): string
    {
    	return _PI_WAITING_INACTIVE_USERS;
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
		return (int)$this->db->fetchValue("SELECT COUNT(*) FROM " . $this->db->prefix('users') . " WHERE level=0");
    }
}