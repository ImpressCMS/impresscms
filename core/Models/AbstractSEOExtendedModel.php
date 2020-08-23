<?php
/**
 * Contains the basis classes for managing any SEO-enabled objects derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */

namespace ImpressCMS\Core\Models;

/**
 * \ImpressCMS\Core\IPF\AbstractModel base SEO-enabled class
 *
 * Base class representing a single \ImpressCMS\Core\IPF\AbstractModel with "search engine optimisation" capabilities
 *
 * @property string  $meta_keywords      Meta keywords
 * @property string  $meta_description   Meta description
 * @property string  $short_url          Short URL
 *
 * @package	ICMS\IPF\SEO
 */
abstract class AbstractSEOExtendedModel extends AbstractExtendedModel
{

	/**
	 * @inheritDoc
	 */
	public function __construct(&$handler, array $data = []) {
		parent::__construct($handler, $data);
		$this->initiateSEO();
	}

	public function initiateSEO() {
		$this->initCommonVar('meta_keywords');
		$this->initCommonVar('meta_description');
		$this->initCommonVar('short_url');
		$this->seoEnabled = true;
	}

	/**
	 * Return the value of the short_url field of this object
	 *
	 * @return string
	 */
	public function short_url() {
		return $this->getVar('short_url');
	}

	/**
	 * Return the value of the meta_keywords field of this object
	 *
	 * @return string
	 */
	public function meta_keywords() {
		return $this->getVar('meta_keywords');
	}

	/**
	 * Return the value of the meta_description field of this object
	 *
	 * @return string
	 */
	public function meta_description() {
		return $this->getVar('meta_description');
	}
}

