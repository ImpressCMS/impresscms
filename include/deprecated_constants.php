<?php

/* * #@+
 * Object datatype
 *
 * */
define('XOBJ_DTYPE_TXTBOX', icms_properties_Handler::DTYPE_DEP_TXTBOX);
define('XOBJ_DTYPE_TXTAREA', icms_properties_Handler::DTYPE_STRING);
define('XOBJ_DTYPE_STRING', icms_properties_Handler::DTYPE_STRING);
define('XOBJ_DTYPE_INT', icms_properties_Handler::DTYPE_INTEGER); // shorthund
define('XOBJ_DTYPE_INTEGER', icms_properties_Handler::DTYPE_INTEGER);
define('XOBJ_DTYPE_URL', icms_properties_Handler::DTYPE_DEP_URL);
define('XOBJ_DTYPE_EMAIL', icms_properties_Handler::DTYPE_DEP_EMAIL);
define('XOBJ_DTYPE_ARRAY', icms_properties_Handler::DTYPE_ARRAY);
define('XOBJ_DTYPE_OTHER', icms_properties_Handler::DTYPE_DEP_OTHER);
define('XOBJ_DTYPE_SOURCE', icms_properties_Handler::DTYPE_DEP_SOURCE);
define('XOBJ_DTYPE_STIME', icms_properties_Handler::DTYPE_DEP_STIME);
define('XOBJ_DTYPE_MTIME', icms_properties_Handler::DTYPE_DEP_MTIME);
define('XOBJ_DTYPE_DATETIME', icms_properties_Handler::DTYPE_DATETIME);
define('XOBJ_DTYPE_LTIME', icms_properties_Handler::DTYPE_DATETIME);


define('XOBJ_DTYPE_SIMPLE_ARRAY', icms_properties_Handler::DTYPE_LIST);
define('XOBJ_DTYPE_CURRENCY', icms_properties_Handler::DTYPE_DEP_CURRENCY);
define('XOBJ_DTYPE_FLOAT', icms_properties_Handler::DTYPE_FLOAT);
define('XOBJ_DTYPE_TIME_ONLY', icms_properties_Handler::DTYPE_DEP_TIME_ONLY);
define('XOBJ_DTYPE_URLLINK', icms_properties_Handler::DTYPE_DEP_URLLINK);
define('XOBJ_DTYPE_FILE', icms_properties_Handler::DTYPE_DEP_FILE);
define('XOBJ_DTYPE_IMAGE', icms_properties_Handler::DTYPE_DEP_IMAGE);
define('XOBJ_DTYPE_FORM_SECTION', icms_properties_Handler::DTYPE_DEP_FORM_SECTION);
define('XOBJ_DTYPE_FORM_SECTION_CLOSE', icms_properties_Handler::DTYPE_DEP_FORM_SECTION_CLOSE);

/* * #@- */

/**#@+
 * Config type
 */
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_MAIN instead!
 */
define('ICMS_CONF', \icms_config_Handler::CATEGORY_MAIN);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_USER instead!
 */
define('ICMS_CONF_USER', \icms_config_Handler::CATEGORY_USER);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_METAFOOTER instead!
 */
define('ICMS_CONF_METAFOOTER', \icms_config_Handler::CATEGORY_METAFOOTER);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_CENSOR instead!
 */
define('ICMS_CONF_CENSOR', \icms_config_Handler::CATEGORY_CENSOR);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_SEARCH instead!
 */
define('ICMS_CONF_SEARCH', \icms_config_Handler::CATEGORY_SEARCH);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_MAILER instead!
 */
define('ICMS_CONF_MAILER', \icms_config_Handler::CATEGORY_MAILER);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_AUTH instead!
 */
define('ICMS_CONF_AUTH', \icms_config_Handler::CATEGORY_AUTH);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_MULILANGUAGE instead!
 */
define('ICMS_CONF_MULILANGUAGE', \icms_config_Handler::CATEGORY_MULILANGUAGE);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_CONTENT instead!
 */
define('ICMS_CONF_CONTENT', \icms_config_Handler::CATEGORY_CONTENT);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_PERSONA instead!
 */
define('ICMS_CONF_PERSONA', \icms_config_Handler::CATEGORY_PERSONA);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_CAPTCHA instead!
 */
define('ICMS_CONF_CAPTCHA', \icms_config_Handler::CATEGORY_CAPTCHA);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_PLUGINS instead!
 */
define('ICMS_CONF_PLUGINS', \icms_config_Handler::CATEGORY_PLUGINS);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_AUTOTASKS instead!
 */
define('ICMS_CONF_AUTOTASKS', \icms_config_Handler::CATEGORY_AUTOTASKS);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_PURIFIER instead!
 */
define('ICMS_CONF_PURIFIER', \icms_config_Handler::CATEGORY_PURIFIER);
/**#@-*/