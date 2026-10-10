<?php
/**
 * Images Manager - Image Editor shared guards
 *
 * Access, CSRF and temp-path checks shared by the image editor and its plugins.
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package core
 */
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

function icms_imageeditor_checkAccess(): void
{
	if (!is_object(icms::$user)) {
		exit(_NOPERM);
	}

	if (in_array(ICMS_GROUP_ANONYMOUS, icms::$user->getGroups())) {
		exit(_NOPERM);
	}

	$systemModule = icms::handler('icms_module')->getByDirname('system');
	if (!is_object($systemModule)) {
		exit(_NOPERM);
	}

	if (!icms::$user->isAdmin($systemModule->getVar('mid'))) {
		exit(_NOPERM);
	}
}

/**
 * Validates the csrf_token request parameter. On failure, answers with a
 * javascript alert, because every caller evals the response.
 */
function icms_imageeditor_checkToken(bool $clearIfValid): void
{
	$token = (string) (filter_input(INPUT_GET, 'csrf_token') ?? filter_input(INPUT_POST, 'csrf_token'));

	if (!icms::$security->check($clearIfValid, $token)) {
		$errors = icms::$security->getErrors();
		$message = empty($errors) ? _CORE_TOKENINVALID : implode("\n", $errors);

		exit('alert(' . json_encode($message) . ');');
	}
}

function icms_imageeditor_tempFolder(): ?string
{
	$tempFolder = realpath(ICMS_IMANAGER_FOLDER_PATH . '/temp');

	return $tempFolder === false ? null : $tempFolder;
}

/**
 * Resolves a path and returns it only when it points to a file inside the image manager temp folder.
 */
function icms_imageeditor_tempPath(?string $path): ?string
{
	if (empty($path)) {
		return null;
	}

	$tempFolder = icms_imageeditor_tempFolder();
	$realPath = realpath($path);

	if ($tempFolder === null || $realPath === false) {
		return null;
	}

	if (!str_starts_with($realPath, $tempFolder . DIRECTORY_SEPARATOR)) {
		return null;
	}

	return $realPath;
}

/** @return array<int, string> */
function icms_imageeditor_filters(): array
{
	return array(
		'IMG_FILTER_NEGATE',
		'IMG_FILTER_GRAYSCALE',
		'IMG_FILTER_BRIGHTNESS',
		'IMG_FILTER_CONTRAST',
		'IMG_FILTER_COLORIZE',
		'IMG_FILTER_EDGEDETECT',
		'IMG_FILTER_EMBOSS',
		'IMG_FILTER_GAUSSIAN_BLUR',
		'IMG_FILTER_SELECTIVE_BLUR',
		'IMG_FILTER_MEAN_REMOVAL',
		'IMG_FILTER_SMOOTH',
		'IMG_FILTER_SEPIA',
	);
}
