<?php

use Phoenix\Migration\AbstractMigration;

class Initialization extends AbstractMigration
{


	/**
	 * Prefix table
	 *
	 * @param string $table Table to prefix
	 *
	 * @return string
	 */
	private function prefix(string $table): string {
		return \icms::getInstance()->get('db-connection-1')->prefix($table);
	}

	/**
	 * Does actions when migrating up
	 */
    protected function up(): void
    {

		if (!$this->tableExists($this->prefix('avatar'))) {
			$this->table($this->prefix('avatar'), 'avatar_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('avatar_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('avatar_file', 'string', ['default' => '', 'length' => 30])
				->addColumn('avatar_name', 'string', ['default' => '', 'length' => 100])
				->addColumn('avatar_mimetype', 'string', ['default' => '', 'length' => 30])
				->addColumn('avatar_created', 'integer', ['default' => 0, 'length' => 10])
				->addColumn('avatar_display', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('avatar_weight', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('avatar_type', 'char', ['default' => '', 'length' => 1])
				->addIndex(['avatar_type', 'avatar_display'], '', 'btree', 'avatar_type')
				->create();
		}

		if (!$this->tableExists($this->prefix('avatar_user_link'))) {
			$this->table($this->prefix('avatar_user_link'))
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('avatar_id', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('user_id', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addIndex(['avatar_id', 'user_id'], '', 'btree', 'avatar_user_id')
				->create();
		}

		if (!$this->tableExists($this->prefix('block_module_link'))) {
			$this->table($this->prefix('block_module_link'))
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('block_id', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('module_id', 'smallinteger', ['default' => 0, 'length' => 5])
				->addColumn('page_id', 'smallinteger', ['default' => 0, 'length' => 5])
				->addIndex('module_id', '', 'btree', 'module_id')
				->addIndex('block_id', '', 'btree', 'block_id')
				->create();
		}

		if (!$this->tableExists($this->prefix('block_positions'))) {
			$this->table($this->prefix('block_positions'), 'id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('id', 'integer', ['autoincrement' => true])
				->addColumn('pname', 'string', ['null' => true, 'default' => '', 'length' => 30])
				->addColumn('title', 'string', ['default' => '', 'length' => 90])
				->addColumn('description', 'text', ['null' => true])
				->addColumn('block_default', 'integer', ['default' => 0, 'length' => 1])
				->addColumn('block_type', 'string', ['default' => 'L', 'length' => 1])
				->create();
		}

		if (!$this->tableExists($this->prefix('config'))) {
			$this->table($this->prefix('config'), 'conf_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('conf_id', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('conf_modid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('conf_catid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('conf_name', 'string', ['default' => '', 'length' => 75])
				->addColumn('conf_title', 'string', ['default' => ''])
				->addColumn('conf_value', 'text')
				->addColumn('conf_desc', 'string', ['default' => ''])
				->addColumn('conf_formtype', 'string', ['default' => '', 'length' => 15])
				->addColumn('conf_valuetype', 'string', ['default' => '', 'length' => 10])
				->addColumn('conf_order', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addIndex(['conf_modid', 'conf_catid', 'conf_order'], '', 'btree', 'mod_cat_order')
				->create();
		}

		if (!$this->tableExists($this->prefix('configcategory'))) {
			$this->table($this->prefix('configcategory'), 'confcat_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('confcat_id', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('confcat_name', 'string', ['default' => ''])
				->addColumn('confcat_order', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->create();
		}

		if (!$this->tableExists($this->prefix('configoption'))) {
			$this->table($this->prefix('configoption'), 'confop_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('confop_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('confop_name', 'string', ['default' => ''])
				->addColumn('confop_value', 'string', ['default' => ''])
				->addColumn('conf_id', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addIndex('conf_id', '', 'btree', 'conf_id')
				->create();
		}

		if (!$this->tableExists($this->prefix('groups'))) {
			$this->table($this->prefix('groups'), 'groupid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('groupid', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('name', 'string', ['default' => '', 'length' => 50])
				->addColumn('description', 'text')
				->addColumn('group_type', 'string', ['default' => '', 'length' => 10])
				->addIndex('group_type', '', 'btree', 'group_type')
				->create();
		}

		if (!$this->tableExists($this->prefix('groups_users_link'))) {
			$this->table($this->prefix('groups_users_link'), 'linkid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('linkid', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('groupid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('uid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addIndex(['groupid', 'uid'], '', 'btree', 'groupid_uid')
				->create();
		}

		if (!$this->tableExists($this->prefix('group_permission'))) {
			$this->table($this->prefix('group_permission'), 'gperm_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('gperm_id', 'integer', ['autoincrement' => true, 'length' => 10, 'signed' => false])
				->addColumn('gperm_groupid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('gperm_itemid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('gperm_modid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('gperm_name', 'string', ['default' => '', 'length' => 50])
				->addIndex(['gperm_name', 'gperm_modid', 'gperm_groupid'], '', 'btree', 'name_mod_group')
				->create();
		}

		if (!$this->tableExists($this->prefix('icmspage'))) {
			$this->table($this->prefix('icmspage'), 'page_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('page_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('page_moduleid', 'mediuminteger', ['default' => 1, 'length' => 8, 'signed' => false])
				->addColumn('page_title', 'string', ['default' => ''])
				->addColumn('page_url', 'string', ['default' => ''])
				->addColumn('page_status', 'boolean', ['default' => true, 'signed' => false])
				->create();
		}

		if (!$this->tableExists($this->prefix('icms_data_file'))) {
			$this->table($this->prefix('icms_data_file'), 'fileid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('fileid', 'integer', ['autoincrement' => true, 'length' => 10, 'signed' => false])
				->addColumn('mid', 'smallinteger', ['length' => 5, 'signed' => false])
				->addColumn('caption', 'string')
				->addColumn('description', 'string')
				->addColumn('url', 'string')
				->addIndex('mid', '', 'btree', 'mid')
				->create();
		}

		if (!$this->tableExists($this->prefix('icms_data_urllink'))) {
			$this->table($this->prefix('icms_data_urllink'), 'urllinkid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('urllinkid', 'integer', ['autoincrement' => true, 'length' => 10, 'signed' => false])
				->addColumn('mid', 'smallinteger', ['length' => 5, 'signed' => false])
				->addColumn('caption', 'string')
				->addColumn('description', 'string')
				->addColumn('url', 'string')
				->addColumn('target', 'string', ['length' => 6])
				->addIndex('mid', '', 'btree', 'mid')
				->create();
		}

		if (!$this->tableExists($this->prefix('image'))) {
			$this->table($this->prefix('image'), 'image_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('image_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('image_name', 'string', ['default' => '', 'length' => 30])
				->addColumn('image_nicename', 'string', ['default' => ''])
				->addColumn('image_mimetype', 'string', ['default' => '', 'length' => 30])
				->addColumn('image_created', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('image_display', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('image_weight', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('imgcat_id', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addIndex('imgcat_id', '', 'btree', 'imgcat_id')
				->addIndex('image_display', '', 'btree', 'image_display')
				->create();
		}

		if (!$this->tableExists($this->prefix('imagebody'))) {
			$this->table($this->prefix('imagebody'))
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('image_id', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('image_body', 'mediumblob', ['null' => true])
				->addIndex('image_id', '', 'btree', 'image_id')
				->create();
		}

		if (!$this->tableExists($this->prefix('imagecategory'))) {
			$this->table($this->prefix('imagecategory'), 'imgcat_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('imgcat_id', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('imgcat_pid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('imgcat_name', 'string', ['default' => '', 'length' => 100])
				->addColumn('imgcat_maxsize', 'integer', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('imgcat_maxwidth', 'smallinteger', ['default' => 0, 'length' => 3, 'signed' => false])
				->addColumn('imgcat_maxheight', 'smallinteger', ['default' => 0, 'length' => 3, 'signed' => false])
				->addColumn('imgcat_display', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('imgcat_weight', 'smallinteger', ['default' => 0, 'length' => 3, 'signed' => false])
				->addColumn('imgcat_type', 'char', ['default' => '', 'length' => 1])
				->addColumn('imgcat_storetype', 'string', ['default' => '', 'length' => 5])
				->addColumn('imgcat_foldername', 'string', ['null' => true, 'default' => '', 'length' => 100])
				->addIndex('imgcat_display', '', 'btree', 'imgcat_display')
				->create();
		}

		if (!$this->tableExists($this->prefix('imgset'))) {
			$this->table($this->prefix('imgset'), 'imgset_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('imgset_id', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('imgset_name', 'string', ['default' => '', 'length' => 50])
				->addColumn('imgset_refid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addIndex('imgset_refid', '', 'btree', 'imgset_refid')
				->create();
		}

		if (!$this->tableExists($this->prefix('imgsetimg'))) {
			$this->table($this->prefix('imgsetimg'), 'imgsetimg_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('imgsetimg_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('imgsetimg_file', 'string', ['default' => '', 'length' => 50])
				->addColumn('imgsetimg_body', 'blob')
				->addColumn('imgsetimg_imgset', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addIndex('imgsetimg_imgset', '', 'btree', 'imgsetimg_imgset')
				->create();
		}

		if (!$this->tableExists($this->prefix('imgset_tplset_link'))) {
			$this->table($this->prefix('imgset_tplset_link'))
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('imgset_id', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('tplset_name', 'string', ['default' => '', 'length' => 50])
				->addIndex('tplset_name', '', 'btree', 'tplset_name')
				->create();
		}

		if (!$this->tableExists($this->prefix('invites'))) {
			$this->table($this->prefix('invites'), 'invite_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('invite_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('from_id', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('invite_to', 'string', ['default' => ''])
				->addColumn('invite_code', 'string', ['default' => '', 'length' => 8])
				->addColumn('invite_date', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('view_date', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('register_id', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('extra_info', 'text')
				->addIndex('invite_code', '', 'btree', 'invite_code')
				->addIndex('register_id', '', 'btree', 'register_id')
				->create();
		}

		if (!$this->tableExists($this->prefix('modules'))) {
			$this->table($this->prefix('modules'), 'mid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('mid', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('name', 'string', ['default' => '', 'length' => 150])
				->addColumn('version', 'smallinteger', ['default' => 102, 'length' => 5, 'signed' => false])
				->addColumn('last_update', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('weight', 'smallinteger', ['default' => 0, 'length' => 3, 'signed' => false])
				->addColumn('isactive', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('dirname', 'string', ['default' => '', 'length' => 25])
				->addColumn('hasmain', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('hasadmin', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('hassearch', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('hasconfig', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('hascomments', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('hasnotification', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('dbversion', 'integer', ['default' => 1, 'signed' => false])
				->addColumn('modname', 'string', ['default' => '', 'length' => 25])
				->addColumn('ipf', 'boolean', ['default' => false, 'signed' => false])
				->addIndex('dirname', '', 'btree', 'dirname')
				->addIndex(['isactive', 'hasmain', 'weight'], '', 'btree', 'active_main_weight')
				->create();
		}

		if (!$this->tableExists($this->prefix('newblocks'))) {
			$this->table($this->prefix('newblocks'), 'bid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('bid', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('mid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('func_num', 'tinyinteger', ['default' => 0, 'length' => 3, 'signed' => false])
				->addColumn('options', 'string', ['default' => ''])
				->addColumn('name', 'string', ['default' => '', 'length' => 150])
				->addColumn('title', 'string', ['default' => ''])
				->addColumn('content', 'text')
				->addColumn('side', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('weight', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('visible', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('block_type', 'char', ['default' => '', 'length' => 1])
				->addColumn('c_type', 'char', ['default' => '', 'length' => 1])
				->addColumn('isactive', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('dirname', 'string', ['default' => '', 'length' => 50])
				->addColumn('func_file', 'string', ['default' => '', 'length' => 50])
				->addColumn('show_func', 'string', ['default' => '', 'length' => 50])
				->addColumn('edit_func', 'string', ['default' => '', 'length' => 50])
				->addColumn('template', 'string', ['default' => '', 'length' => 50])
				->addColumn('bcachetime', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('last_modified', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addIndex('mid', '', 'btree', 'mid')
				->addIndex('visible', '', 'btree', 'visible')
				->addIndex(['isactive', 'visible', 'mid'], '', 'btree', 'isactive_visible_mid')
				->addIndex(['mid', 'func_num'], '', 'btree', 'mid_funcnum')
				->create();
		}

		if (!$this->tableExists($this->prefix('online'))) {
			$this->table($this->prefix('online'))
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('online_uid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('online_uname', 'string', ['default' => '', 'length' => 25])
				->addColumn('online_updated', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('online_module', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('online_ip', 'string', ['default' => '', 'length' => 15])
				->addIndex('online_module', '', 'btree', 'online_module')
				->create();
		}

		if (!$this->tableExists($this->prefix('priv_msgs'))) {
			$this->table($this->prefix('priv_msgs'), 'msg_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('msg_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('msg_image', 'string', ['null' => true, 'length' => 100])
				->addColumn('subject', 'string', ['default' => ''])
				->addColumn('from_userid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('to_userid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('msg_time', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('msg_text', 'text')
				->addColumn('read_msg', 'boolean', ['default' => false, 'signed' => false])
				->addIndex(['to_userid', 'read_msg'], '', 'btree', 'touseridreadmsg')
				->addIndex(['msg_id', 'from_userid'], '', 'btree', 'msgidfromuserid')
				->create();
		}

		if (!$this->tableExists($this->prefix('ranks'))) {
			$this->table($this->prefix('ranks'), 'rank_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('rank_id', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('rank_title', 'string', ['default' => '', 'length' => 50])
				->addColumn('rank_min', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('rank_max', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('rank_special', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('rank_image', 'string', ['null' => true])
				->addIndex('rank_max', '', 'btree', 'rank_max')
				->addIndex(['rank_min', 'rank_max', 'rank_special'], '', 'btree', 'rankminrankmaxranspecial')
				->addIndex('rank_special', '', 'btree', 'rankspecial')
				->create();
		}

		if (!$this->tableExists($this->prefix('session'))) {
			$this->table($this->prefix('session'), 'sess_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('sess_id', 'string', ['default' => '', 'length' => 32])
				->addColumn('sess_updated', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('sess_ip', 'string', ['default' => '', 'length' => 64])
				->addColumn('sess_data', 'text')
				->addIndex('sess_updated', '', 'btree', 'updated')
				->create();
		}

		if (!$this->tableExists($this->prefix('smiles'))) {
			$this->table($this->prefix('smiles'), 'id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('id', 'smallinteger', ['autoincrement' => true, 'length' => 5, 'signed' => false])
				->addColumn('code', 'string', ['default' => '', 'length' => 50])
				->addColumn('smile_url', 'string', ['default' => '', 'length' => 100])
				->addColumn('emotion', 'string', ['default' => '', 'length' => 75])
				->addColumn('display', 'boolean', ['default' => false])
				->create();
		}

		if (!$this->tableExists($this->prefix('system_adsense'))) {
			$this->table($this->prefix('system_adsense'), 'adsenseid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('adsenseid', 'integer', ['autoincrement' => true])
				->addColumn('format', 'string', ['length' => 100])
				->addColumn('description', 'text')
				->addColumn('style', 'text')
				->addColumn('color_border', 'string', ['default' => '', 'length' => 6])
				->addColumn('color_background', 'string', ['default' => '', 'length' => 6])
				->addColumn('color_link', 'string', ['default' => '', 'length' => 6])
				->addColumn('color_url', 'string', ['default' => '', 'length' => 6])
				->addColumn('color_text', 'string', ['default' => '', 'length' => 6])
				->addColumn('client_id', 'string', ['default' => '', 'length' => 100])
				->addColumn('tag', 'string', ['default' => '', 'length' => 50])
				->addColumn('slot', 'string', ['default' => '', 'length' => 12])
				->create();
		}

		if (!$this->tableExists($this->prefix('system_autotasks'))) {
			$this->table($this->prefix('system_autotasks'), 'sat_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('sat_id', 'integer', ['autoincrement' => true, 'length' => 10, 'signed' => false])
				->addColumn('sat_name', 'string')
				->addColumn('sat_code', 'text')
				->addColumn('sat_repeat', 'integer')
				->addColumn('sat_interval', 'integer')
				->addColumn('sat_onfinish', 'smallinteger', ['length' => 2])
				->addColumn('sat_enabled', 'integer', ['length' => 1])
				->addColumn('sat_lastruntime', 'integer', ['length' => 15, 'signed' => false])
				->addColumn('sat_type', 'string', ['default' => 'custom', 'length' => 100])
				->addColumn('sat_addon_id', 'integer', ['null' => true, 'length' => 2])
				->addIndex('sat_interval', '', 'btree', 'sat_interval')
				->addIndex('sat_lastruntime', '', 'btree', 'sat_lastruntime')
				->addIndex('sat_type', '', 'btree', 'sat_type')
				->create();
		}

		if (!$this->tableExists($this->prefix('system_customtag'))) {
			$this->table($this->prefix('system_customtag'), 'customtagid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('customtagid', 'integer', ['autoincrement' => true, 'signed' => false])
				->addColumn('name', 'string', ['default' => ''])
				->addColumn('description', 'text')
				->addColumn('customtag_content', 'text')
				->addColumn('language', 'string', ['default' => '', 'length' => 100])
				->addColumn('customtag_type', 'boolean', ['default' => false])
				->create();
		}

		if (!$this->tableExists($this->prefix('system_mimetype'))) {
			$this->table($this->prefix('system_mimetype'), 'mimetypeid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('mimetypeid', 'integer', ['autoincrement' => true])
				->addColumn('extension', 'string', ['default' => '', 'length' => 60])
				->addColumn('types', 'text')
				->addColumn('name', 'string', ['default' => ''])
				->addColumn('dirname', 'string')
				->create();
		}

		if (!$this->tableExists($this->prefix('system_rating'))) {
			$this->table($this->prefix('system_rating'), 'ratingid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('ratingid', 'integer', ['autoincrement' => true])
				->addColumn('dirname', 'string')
				->addColumn('item', 'string')
				->addColumn('itemid', 'integer')
				->addColumn('uid', 'integer')
				->addColumn('rate', 'integer', ['length' => 1])
				->addColumn('date', 'integer')
				->create();
		}

		if (!$this->tableExists($this->prefix('tplfile'))) {
			$this->table($this->prefix('tplfile'), 'tpl_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('tpl_id', 'mediuminteger', ['autoincrement' => true, 'length' => 7, 'signed' => false])
				->addColumn('tpl_refid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('tpl_module', 'string', ['default' => '', 'length' => 25])
				->addColumn('tpl_tplset', 'string', ['default' => '', 'length' => 50])
				->addColumn('tpl_file', 'string', ['default' => '', 'length' => 50])
				->addColumn('tpl_desc', 'string', ['default' => ''])
				->addColumn('tpl_lastmodified', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('tpl_lastimported', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('tpl_type', 'string', ['default' => '', 'length' => 20])
				->addIndex(['tpl_refid', 'tpl_type'], '', 'btree', 'tpl_refid')
				->addIndex(['tpl_tplset', 'tpl_file'], '', 'btree', 'tpl_tplset')
				->create();
		}

		if (!$this->tableExists($this->prefix('tplset'))) {
			$this->table($this->prefix('tplset'), 'tplset_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('tplset_id', 'integer', ['autoincrement' => true, 'length' => 7, 'signed' => false])
				->addColumn('tplset_name', 'string', ['default' => '', 'length' => 50])
				->addColumn('tplset_desc', 'string', ['default' => ''])
				->addColumn('tplset_credits', 'text')
				->addColumn('tplset_created', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->create();
		}

		if (!$this->tableExists($this->prefix('tplsource'))) {
			$this->table($this->prefix('tplsource'))
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('tpl_id', 'mediuminteger', ['default' => 0, 'length' => 7, 'signed' => false])
				->addColumn('tpl_source', 'mediumtext')
				->addIndex('tpl_id', '', 'btree', 'tpl_id')
				->create();
		}

		if (!$this->tableExists($this->prefix('users'))) {
			$this->table($this->prefix('users'), 'uid')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('uid', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('name', 'string', ['default' => '', 'length' => 60])
				->addColumn('uname', 'string', ['default' => ''])
				->addColumn('email', 'string', ['default' => ''])
				->addColumn('url', 'string', ['default' => ''])
				->addColumn('user_avatar', 'string', ['default' => 'blank.gif', 'length' => 30])
				->addColumn('user_regdate', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('user_from', 'string', ['default' => '', 'length' => 100])
				->addColumn('user_sig', 'text')
				->addColumn('user_viewemail', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('actkey', 'string', ['default' => '', 'length' => 8])
				->addColumn('pass', 'string', ['default' => ''])
				->addColumn('posts', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('attachsig', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('rank', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('level', 'string', ['default' => 1, 'length' => 3])
				->addColumn('theme', 'string', ['default' => '', 'length' => 100])
				->addColumn('timezone_offset', 'float', ['default' => 0.0, 'length' => 3, 'decimals' => 1])
				->addColumn('last_login', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('umode', 'string', ['default' => '', 'length' => 10])
				->addColumn('uorder', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('notify_method', 'boolean', ['default' => true])
				->addColumn('notify_mode', 'boolean', ['default' => false])
				->addColumn('user_occ', 'string', ['default' => '', 'length' => 100])
				->addColumn('bio', 'tinytext')
				->addColumn('user_intrest', 'string', ['default' => '', 'length' => 150])
				->addColumn('user_mailok', 'boolean', ['default' => true, 'signed' => false])
				->addColumn('language', 'string', ['default' => '', 'length' => 100])
				->addColumn('openid', 'string', ['default' => ''])
				->addColumn('salt', 'string', ['default' => ''])
				->addColumn('user_viewoid', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('pass_expired', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('enc_type', 'tinyinteger', ['default' => 1, 'length' => 2, 'signed' => false])
				->addColumn('login_name', 'string', ['default' => ''])
				->addIndex('login_name', 'unique', 'btree', 'login_name')
				->addIndex('uname', '', 'btree', 'uname')
				->create();
		}

		if (!$this->tableExists($this->prefix('xoopscomments'))) {
			$this->table($this->prefix('xoopscomments'), 'com_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('com_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('com_pid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('com_rootid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('com_modid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('com_itemid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('com_icon', 'string', ['default' => '', 'length' => 25])
				->addColumn('com_created', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('com_modified', 'integer', ['default' => 0, 'length' => 10, 'signed' => false])
				->addColumn('com_uid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('com_ip', 'string', ['default' => '', 'length' => 15])
				->addColumn('com_title', 'string', ['default' => ''])
				->addColumn('com_text', 'text')
				->addColumn('com_sig', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('com_status', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('com_exparams', 'string', ['default' => ''])
				->addColumn('dohtml', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('dosmiley', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('doxcode', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('doimage', 'boolean', ['default' => false, 'signed' => false])
				->addColumn('dobr', 'boolean', ['default' => false, 'signed' => false])
				->addIndex('com_pid', '', 'btree', 'com_pid')
				->addIndex('com_itemid', '', 'btree', 'com_itemid')
				->addIndex('com_uid', '', 'btree', 'com_uid')
				->addIndex('com_title', '', 'btree', 'com_title')
				->create();
		}

		if (!$this->tableExists($this->prefix('xoopsnotifications'))) {
			$this->table($this->prefix('xoopsnotifications'), 'not_id')
				->setCharset('utf8')
				->setCollation('utf8_general_ci')
				->addColumn('not_id', 'mediuminteger', ['autoincrement' => true, 'length' => 8, 'signed' => false])
				->addColumn('not_modid', 'smallinteger', ['default' => 0, 'length' => 5, 'signed' => false])
				->addColumn('not_itemid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('not_category', 'string', ['default' => '', 'length' => 30])
				->addColumn('not_event', 'string', ['default' => '', 'length' => 30])
				->addColumn('not_uid', 'mediuminteger', ['default' => 0, 'length' => 8, 'signed' => false])
				->addColumn('not_mode', 'boolean', ['default' => false])
				->addIndex('not_modid', '', 'btree', 'not_modid')
				->addIndex('not_itemid', '', 'btree', 'not_itemid')
				->addIndex('not_category', '', 'btree', 'not_class')
				->addIndex('not_uid', '', 'btree', 'not_uid')
				->addIndex('not_event', '', 'btree', 'not_event')
				->create();
		}


    }

	/**
	 * Executes when migration goes down
	 */
    protected function down(): void
    {
    	foreach ([
					 'avatar',
					 'avatar_user_link',
					 'block_module_link',
					 'block_positions',
					 'config',
					 'configcategory',
					 'configoption',
					 'groups',
					 'groups_users_link',
					 'group_permission',
					 'icmspage',
					 'icms_data_file',
					 'icms_data_urllink',
					 'image',
					 'imagebody',
					 'imagecategory',
					 'imgset',
					 'imgsetimg',
					 'imgset_tplset_link',
					 'invites',
					 'modules',
					 'newblocks',
					 'online',
					 'priv_msgs',
					 'ranks',
					 'session',
					 'smiles',
					 'system_adsense',
					 'system_autotasks',
					 'system_customtag',
					 'system_mimetype',
					 'system_rating',
					 'tplfile',
					 'tplset',
					 'tplsource',
					 'users',
					 'xoopscomments',
					 'xoopsnotifications'
				 ] as $table) {
    		$prefixedTable = $this->prefix($table);
			if (!$this->tableExists($prefixedTable)) {
				continue;
			}
			$this->table($prefixedTable)
				->drop();
		}
    }
}
