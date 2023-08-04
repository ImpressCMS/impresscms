<?php
/**
 * googleanalytics ga4.php
 * Created by david on 13/02/2022 22:41
 * Modified by skenow on 11 June 2023
 */

/**
 * preload class to include the Google Analytics V4 tag in the Head of the webpage, after replacing the smarty variable with the GA4 property code
 * This can certainly be improved by checking whether we can, based on the preferences by our users (GDPR and cookies)
 */
class IcmsPreloadGa4 extends IcmsPreloadItem {

	function eventBeforeFooter() {
		global $xoopsTpl;
		global $icmsTheme;
		global $icmsConfigMetaFooter;
		global $xoTheme;

		try {

			if ($icmsConfigMetaFooter['use_google_analytics'] == TRUE && isset($icmsConfigMetaFooter['google_analytics']) && $icmsConfigMetaFooter['google_analytics'] != '') {
				if (substr($icmsConfigMetaFooter['google_analytics'], 0, 2 ) === "G-") {
					$tagvalue = $icmsConfigMetaFooter['google_analytics'];
				} else {
					$tagvalue = 'UA-' . $icmsConfigMetaFooter['google_analytics'];
				}
				$xoTheme->addScript('https://www.googletagmanager.com/gtag/js?id=' . $tagvalue, ['async'=>'async'], '', 'module', 2000);
				$xoTheme->addScript('', '', 'window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag(\'js\', new Date());

    gtag(\'config\', \'' . $tagvalue . '\');', 'module', '2001');
			} else {
				echo 'error getting icmsConfigMetaFooter';
			}
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}
