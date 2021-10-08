<?php

namespace ImpressCMS\Modules\System\Extensions;

/**
 * Interface that must be implemented when need to extend waiting info block info
 *
 * All classes that implements this interface must be defined in container with
 * "module.system.waiting-info-getter" tag.
 *
 * @package ImpressCMS\Modules\System\Extensions
 */
interface WaitingInfoGetterInterface
{
	/**
	 * Gets link URL for page where user can see waiting content
	 *
	 * @return string
	 */
	public function getLinkUrl(): string;

	/**
	 * Gets link title for page where user can see waiting content
	 *
	 * @return string
	 */
	public function getLinkTitle(): string;

	/**
	 * Gets count how many content is waiting there
	 *
	 * @return int
	 */
	public function getCount(): int;


}