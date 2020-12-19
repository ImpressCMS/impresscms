<?php

namespace ImpressCMS\Modules\System\Extensions\WaitingInfoGetter;

use ImpressCMS\Modules\System\Extensions\WaitingInfoGetterInterface;

/**
 * Gets info about waiting comments
 *
 * @package ImpressCMS\Modules\System\Extensions\WaitingInfoGetter
 */
class CommentsWaitingInfoGetter implements WaitingInfoGetterInterface
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
		return ICMS_URL . '/modules/system/admin.php?module=0&amp;status=1&amp;fct=comments';
	}

	/**
	 * @inheritDoc
	 */
	public function getLinkTitle(): string
	{
		return _PI_WAITING_COMMENTS;
	}

	/**
	 * @inheritDoc
	 */
	public function getCount(): int
	{
		return (int)$this->db->fetchValue("SELECT COUNT(*) FROM " . $this->db->prefix('xoopscomments') . " WHERE com_status=1");
	}
}