<?php
/**
 * googleanalytics ga4.php
 * Created by david on 13/02/2022 22:41
 *
 */

/**
 * preload class to include the Google Analytics V4 tag in the Head of the webpage, after replacing the smarty variable with the GA4 property code
 * This can certainly be improved by checking whether we can, based on the preferences by our users (GDPR and cookies)
 */
class IcmsPreloadGoogleanalytics extends icms_preload_Item {

    function eventBeforeFooter()
    {
        global $xoopsTpl;
        global $icmsTheme;
        global $icmsConfigMetaFooter;


        try {

            if ($icmsConfigMetaFooter['use_google_analytics'] && isset($icmsConfigMetaFooter['google_analytics']) && $icmsConfigMetaFooter['google_analytics'] != '') {
				if(substr( $icmsConfigMetaFooter['use_google_analytics'], 0, 2 ) === "G-")
				{
					$this->insertGA4Tag($icmsConfigMetaFooter['use_google_analytics']);
				}
				else
				{
					$this->insertUniversalAnalyticsTag($icmsConfigMetaFooter['use_google_analytics']);
				}
            }
            else {
                echo 'error getting icmsConfigMetaFooter';
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

	function insertUniversalAnalyticsTag(string $UAtag)
	{
		$icmsTheme->addScript('', '', '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');

  ga(\'create\', \'UA-' . $UAtag  . '\', \'auto\');
  ga(\'send\', \'pageview\');', 'module', '2001');

	}

	function insertGA4Tag(string $GA4tag)
	{
		$icmsTheme->addScript('https://www.googletagmanager.com/gtag/js?id=' . $GA4tag, ['async'], '', 'module', 2000);
		$icmsTheme->addScript('', '', 'window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag(\'js\', new Date());

    gtag(\'config\', \'' . $GA4tag . '\');', 'module', '2001');

	}
}