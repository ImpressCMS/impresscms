<?php

/* * #@+
 * Object datatype
 *
 * */
define('XOBJ_DTYPE_TXTBOX', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_TXTBOX);
define('XOBJ_DTYPE_TXTAREA', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_STRING);
define('XOBJ_DTYPE_STRING', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_STRING);
define('XOBJ_DTYPE_INT', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_INTEGER); // shorthund
define('XOBJ_DTYPE_INTEGER', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_INTEGER);
define('XOBJ_DTYPE_URL', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_URL);
define('XOBJ_DTYPE_EMAIL', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_EMAIL);
define('XOBJ_DTYPE_ARRAY', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_ARRAY);
define('XOBJ_DTYPE_OTHER', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_OTHER);
define('XOBJ_DTYPE_SOURCE', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_SOURCE);
define('XOBJ_DTYPE_STIME', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_STIME);
define('XOBJ_DTYPE_MTIME', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_MTIME);
define('XOBJ_DTYPE_DATETIME', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DATETIME);
define('XOBJ_DTYPE_LTIME', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DATETIME);


define('XOBJ_DTYPE_SIMPLE_ARRAY', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_LIST);
define('XOBJ_DTYPE_CURRENCY', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_CURRENCY);
define('XOBJ_DTYPE_FLOAT', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_FLOAT);
define('XOBJ_DTYPE_TIME_ONLY', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_TIME_ONLY);
define('XOBJ_DTYPE_URLLINK', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_URLLINK);
define('XOBJ_DTYPE_FILE', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_FILE);
define('XOBJ_DTYPE_IMAGE', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_IMAGE);
define('XOBJ_DTYPE_FORM_SECTION', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_FORM_SECTION);
define('XOBJ_DTYPE_FORM_SECTION_CLOSE', \ImpressCMS\Core\Properties\AbstractProperties::DTYPE_DEP_FORM_SECTION_CLOSE);

/* * #@- */

/**#@+
 * Config type
 */
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_MAIN instead!
 */
define('ICMS_CONF', \ImpressCMS\Core\Facades\Config::CATEGORY_MAIN);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_USER instead!
 */
define('ICMS_CONF_USER', \ImpressCMS\Core\Facades\Config::CATEGORY_USER);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_METAFOOTER instead!
 */
define('ICMS_CONF_METAFOOTER', \ImpressCMS\Core\Facades\Config::CATEGORY_METAFOOTER);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_CENSOR instead!
 */
define('ICMS_CONF_CENSOR', \ImpressCMS\Core\Facades\Config::CATEGORY_CENSOR);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_SEARCH instead!
 */
define('ICMS_CONF_SEARCH', \ImpressCMS\Core\Facades\Config::CATEGORY_SEARCH);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_MAILER instead!
 */
define('ICMS_CONF_MAILER', \ImpressCMS\Core\Facades\Config::CATEGORY_MAILER);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_AUTH instead!
 */
define('ICMS_CONF_AUTH', \ImpressCMS\Core\Facades\Config::CATEGORY_AUTH);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_MULILANGUAGE instead!
 */
define('ICMS_CONF_MULILANGUAGE', \ImpressCMS\Core\Facades\Config::CATEGORY_MULILANGUAGE);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_CONTENT instead!
 */
define('ICMS_CONF_CONTENT', \ImpressCMS\Core\Facades\Config::CATEGORY_CONTENT);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_PERSONA instead!
 */
define('ICMS_CONF_PERSONA', \ImpressCMS\Core\Facades\Config::CATEGORY_PERSONA);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_CAPTCHA instead!
 */
define('ICMS_CONF_CAPTCHA', \ImpressCMS\Core\Facades\Config::CATEGORY_CAPTCHA);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_PLUGINS instead!
 */
define('ICMS_CONF_PLUGINS', \ImpressCMS\Core\Facades\Config::CATEGORY_PLUGINS);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_AUTOTASKS instead!
 */
define('ICMS_CONF_AUTOTASKS', \ImpressCMS\Core\Facades\Config::CATEGORY_AUTOTASKS);
/**
 * @deprecated 2.0 Use \ImpressCMS\Core\Facades\Config::CATEGORY_PURIFIER instead!
 */
define('ICMS_CONF_PURIFIER', \ImpressCMS\Core\Facades\Config::CATEGORY_PURIFIER);
/**#@-*/