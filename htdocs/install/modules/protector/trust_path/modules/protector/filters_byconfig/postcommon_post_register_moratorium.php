<?php
define('PROTECTOR_POSTCOMMON_POST_REGISTER_MORATORIUM', 60);

// minutes
class protector_postcommon_post_register_moratorium extends ProtectorFilterAbstract {

	function execute() {

		if (!is_object(icms::$user)) {
			return true;
		}

		$moratorium_result = (int) ((icms::$user->getVar('user_regdate') + PROTECTOR_POSTCOMMON_POST_REGISTER_MORATORIUM * 60 - time()) / 60);
		if ($moratorium_result > 0) {
			if (preg_match('#(https?\:|\[\/url\]|www\.)#', serialize($_POST))) {
				$message = sprintf(_MD_PROTECTOR_FMT_REGISTER_MORATORIUM, $moratorium_result);
				$this->protector->message .= $message . '(' . serialize($_POST) . ')';
				$this->protector->output_log('Moratorium', 0, false, 128);
				die($message);
			}
		}
	}
}
