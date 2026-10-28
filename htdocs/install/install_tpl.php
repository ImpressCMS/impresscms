<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * Installer tables creation page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package        installer
 * @since       2.3.0
 * @author        Haruki Setoyama  <haruki@planewave.org>
 * @author        Kazumi Ono <webmaster@myweb.ne.jp>
 * @author        Skalpa Keo <skalpa@xoops.org>
 * @author        Taiwen Jiang <phppp@users.sourceforge.net>
 * @version        $Id: install_tpl.php 12329 2013-09-19 13:53:36Z skenow $
 */
defined('XOOPS_INSTALL') or die();
if (isset($_COOKIE['xo_install_lang'])) {
	$icmsConfig['language'] = htmlentities($_COOKIE['xo_install_lang']);
}

$isRtl = defined('_ADM_USE_RTL') && _ADM_USE_RTL;
$totalPages = count($wizard->pages);
$stepNumber = $wizard->currentPage + 1;
$progressPercent = (int) round($stepNumber / $totalPages * 100);
$isLastPage = $wizard->pages[$wizard->currentPage] === $wizard->lastpage;
$isSecondLastPage = $wizard->pages[$wizard->currentPage] === $wizard->secondlastpage;
$langCode = defined('_LANGCODE') ? _LANGCODE : 'en';
$stepLabel = sprintf('%s %d%s%d', INSTALL_STEP, $stepNumber, INSTALL_OUTOF, $totalPages);
$pageTitle = $wizard->pagesTitles[$wizard->currentPage];
$wizardTitle = sprintf(XOOPS_INSTALL_WIZARD, XOOPS_VERSION);
$nextLabel = (isset($_POST['mod']) && $_POST['mod'] == 1 && $isSecondLastPage) ? BUTTON_FINISH : BUTTON_NEXT;

?><!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($langCode); ?>" dir="<?php echo $isRtl ? 'rtl' : 'ltr'; ?>">
<head>
	<meta charset="<?php echo _INSTALL_CHARSET ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light dark">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo $pageTitle; ?> &ndash; <?php echo $wizardTitle; ?> (<?php echo $stepNumber . '/' . $totalPages; ?>)</title>
	<link rel="stylesheet" href="style.css">
	<noscript><style>.xoform-help { display: block; } .help-toggle { display: none; }</style></noscript>
	<script src="../libraries/jquery/jquery.js"></script>
</head>
<body>
<a class="skip-link" href="#content"><?php echo ESS_SKIP_TO_CONTENT; ?></a>

<header class="site-header">
	<div class="container site-header__inner">
		<img class="site-header__logo" src="img/logo.png" alt="ImpressCMS" width="170" height="34">
		<p class="site-header__info">
			<span class="site-header__title"><?php echo $wizardTitle; ?></span>
			<span class="site-header__step"><?php echo $stepLabel; ?></span>
		</p>
	</div>
	<div class="progress" role="presentation"><div class="progress__bar" style="width: <?php echo $progressPercent; ?>%"></div></div>
</header>

<div class="container layout">
	<nav class="steps" aria-label="<?php echo INSTALL_H3_STEPS; ?>">
		<p class="steps__summary"><?php echo $stepLabel; ?>: <strong><?php echo $wizard->pagesNames[$wizard->currentPage]; ?></strong></p>
		<h2 class="steps__title"><?php echo INSTALL_H3_STEPS; ?></h2>
		<ol class="steps__list">
			<?php foreach ($wizard->pages as $k => $page) {
				$name = $wizard->pagesNames[$k];
				if ($k == $wizard->currentPage) {
					echo '<li class="steps__item is-current"><span class="steps__label" aria-current="step">' . $name . '</span></li>' . "\n";
					continue;
				}
				if ($k > $wizard->currentPage) {
					echo '<li class="steps__item is-upcoming"><span class="steps__label">' . $name . '</span></li>' . "\n";
					continue;
				}
				echo '<li class="steps__item is-done"><a class="steps__label" href="' . $wizard->pageURI($page) . '">' . $name . '</a></li>' . "\n";
			} ?>
		</ol>
	</nav>

	<main id="content" class="card" tabindex="-1">
		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="card__form">
			<div class="page" id="<?php echo $wizard->currentPageName; ?>">
				<div class="page__header">
					<h1 class="page__title"><?php echo $pageTitle; ?></h1>
					<?php if (!empty($pageHasHelp)) { ?>
						<button type="button" class="btn btn--ghost help-toggle" id="help_button"
								aria-expanded="false" aria-controls="content">
							<svg aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
							<?php echo SHOW_HIDE_HELP; ?>
						</button>
					<?php } ?>
				</div>
				<div class="page__body">
					<?php echo $content; ?>
				</div>
			</div>

			<div class="actions" id="buttons">
				<?php if ($wizard->currentPage != 0 && !$isLastPage) { ?>
					<a class="btn btn--secondary prev" href="<?php echo $wizard->pageURI('-1'); ?>">
						<svg aria-hidden="true" focusable="false" class="icon-dir" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
						<?php echo BUTTON_PREVIOUS; ?>
					</a>
				<?php } ?>
				<?php if ($isLastPage) { ?>
					<a class="btn btn--primary finish" id="hmo" href="<?php echo $wizard->pageURI('+0'); ?>?success=true">
						<?php echo BUTTON_SHOW_SITE; ?>
						<svg aria-hidden="true" focusable="false" class="icon-dir" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
					</a>
				<?php } else { ?>
					<?php if (!empty($pageHasForm)) { ?>
						<button type="submit" class="btn btn--primary next" accesskey="n">
					<?php } else { ?>
						<a class="btn btn--primary next" accesskey="n" href="<?php echo $wizard->pageURI('+1'); ?>">
					<?php } ?>
						<?php echo $nextLabel; ?>
						<svg aria-hidden="true" focusable="false" class="icon-dir" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
					<?php echo !empty($pageHasForm) ? '</button>' : '</a>'; ?>
				<?php } ?>
			</div>
		</form>
	</main>
</div>

<footer class="site-footer">
	<div class="container"><?php echo INSTALL_COPYRIGHT; ?></div>
</footer>

<script>
	(function () {
		var helpButton = document.getElementById('help_button');
		if (!helpButton) {
			return;
		}
		helpButton.addEventListener('click', function () {
			var open = document.body.classList.toggle('show-help');
			helpButton.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	})();
</script>
</body>
</html>
