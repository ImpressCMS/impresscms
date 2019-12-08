<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     resource.db.php
 * Type:     resource
 * Name:     db
 * Purpose:  Fetches templates from a database
 * -------------------------------------------------------------
 */

class Smarty_Resource_Db extends Smarty_Resource_Custom
{

	/**
	 * @inheritDoc
	 */
	protected function fetch($name, &$source, &$mtime)
	{
		if (!$tpl = $this->tplinfo($name)) {
			$mtime = null;
			$source = null;
			return;
		}
		if (is_object($tpl)) {
			$source = $tpl->getVar('tpl_source', 'n');
			$mtime = $tpl->getVar('tpl_lastmodified', 'n');
		} else {
			$source = file_get_contents($tpl);
			$mtime = filemtime($tpl);
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function fetchTimestamp($name)
	{
		if (!$tpl = $this->tplinfo($name)) {
			return 0;
		}
		if (is_object($tpl)) {
			return $tpl->getVar('tpl_lastmodified', 'n');
		}
		return filemtime($tpl);
	}

	/**
	 * Gets template from database
	 *
	 * @param string $tpl_name Template name
	 *
	 * @return bool|mixed|string
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	protected function tplinfo($tpl_name)
	{
		global $icmsConfig;

		/**
		 * @var \Psr\Cache\CacheItemPoolInterface $cache
		 */
		$cache = \icms::getInstance()->get('cache');
		$cachedTemplate = $cache->getItem('tpl_db_' . base64_encode($tpl_name));

		if ($cachedTemplate->isHit()) {
			return $cachedTemplate->get();
		}

		$tplset = $icmsConfig['template_set'];
		$theme = $icmsConfig['theme_set'] ?? 'default';

		$tplfile_handler = icms::handler('icms_view_template_file');
		// If we're not using the "default" template set, then get the templates from the DB
		if ($tplset != 'default') {
			$tplobj = $tplfile_handler->getPrefetchedBlock($tplset, $tpl_name);
			if (count($tplobj)) {
				$cachedTemplate->set($tplobj[0]);
				return $tplobj[0];
			}
		}
		// If we'using the default tplset, get the template from the filesystem
		$tplobj = $tplfile_handler->getPrefetchedBlock('default', $tpl_name);

		if (!count($tplobj)) {
			$cachedTemplate->set(false);
			return false;
		}
		$module = $tplobj[0]->getVar('tpl_module', 'n');
		$type = $tplobj[0]->getVar('tpl_type', 'n');
		$blockpath = ($type == 'block') ? 'blocks/' : '';
		// First, check for an overloaded version within the theme folder
		$filepath = ICMS_THEME_PATH . "/$theme/modules/$module/$blockpath$tpl_name";
		if ( !file_exists( $filepath ) ) {
			// If no custom version exists, get the tpl from its default location
			$filepath = ICMS_ROOT_PATH . "/modules/$module/templates/$blockpath$tpl_name";
			if (!file_exists($filepath)) {
				$cachedTemplate->set($tplobj[0]);
				return $tplobj[0];
			}
		}
		$cachedTemplate->set($filepath);
		return $filepath;
	}

}