<?php
/**
 * Extended User Profile
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

/**
    * Get {@link XoopsThemeForm} for adding/editing fields
    *
    * @param object $field {@link XoopsProfileField} object to get edit form for
    * @param mixed $action URL to submit to - or false for $_SERVER['REQUEST_URI']
    *
    * @return object
    */

function getFieldForm(&$field, $action = false) {
    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
    $title = $field->isNew() ? sprintf(_PROFILE_AM_ADD, _PROFILE_AM_FIELD) : sprintf(_PROFILE_AM_EDIT, _PROFILE_AM_FIELD);

    include_once(ICMS_ROOT_PATH."/class/xoopsformloader.php");
    $form = new XoopsThemeForm($title, 'form', $action, 'post', true);

    $form->addElement(new XoopsFormText(sprintf(_PROFILE_AM_TITLE, _PROFILE_AM_FIELD), 'field_title', 35, 255, $field->getVar('field_title', 'e')));
    $form->addElement(new XoopsFormTextArea(_PROFILE_AM_DESCRIPTION, 'field_description', $field->getVar('field_description', 'e')));

    if (!$field->isNew()) {
        $fieldcatid = $field->getVar('catid');
    }
    else {
        $fieldcatid = 0;
    }
    $category_handler =& icms_getmodulehandler( 'category', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
    $cat_select = new XoopsFormSelect(_PROFILE_AM_CATEGORY, 'field_category', $fieldcatid);
    $cat_select->addOption(0, _PROFILE_AM_DEFAULT);
    $cat_select->addOptionArray($category_handler->getList());
    $form->addElement($cat_select);
    $form->addElement(new XoopsFormText(_PROFILE_AM_WEIGHT, 'field_weight', 10, 10, $field->getVar('field_weight', 'e')));
    if ($field->getVar('field_config') || $field->isNew()) {
        if (!$field->isNew()) {
            $form->addElement(new XoopsFormLabel(sprintf(_PROFILE_AM_NAME, _PROFILE_AM_FIELD), $field->getVar('field_name')));
            $form->addElement(new XoopsFormHidden('id', $field->getVar('fieldid')));
        }
        else {
            $form->addElement(new XoopsFormText(sprintf(_PROFILE_AM_NAME, _PROFILE_AM_FIELD), 'field_name', 35, 255, $field->getVar('field_name', 'e')));
        }

        //autotext and theme left out of this one as fields of that type should never be changed (valid assumption, I think)
        $fieldtypes = array('checkbox' => _PROFILE_AM_CHECKBOX,
        'date' => _PROFILE_AM_DATE,
        'datetime' => _PROFILE_AM_DATETIME,
        'longdate' => _PROFILE_AM_LONGDATE,
        'group' => _PROFILE_AM_GROUP,
        'group_multi' => _PROFILE_AM_GROUPMULTI,
        'language' => _PROFILE_AM_LANGUAGE,
        'radio' => _PROFILE_AM_RADIO,
        'select' => _PROFILE_AM_SELECT,
        'select_multi' => _PROFILE_AM_SELECTMULTI,
        'textarea' => _PROFILE_AM_TEXTAREA,
        'dhtml' => _PROFILE_AM_DHTMLTEXTAREA,
        'textbox' => _PROFILE_AM_TEXTBOX,
        'timezone' => _PROFILE_AM_TIMEZONE,
        'image' => "IMAGE",
        'yesno' => _PROFILE_AM_YESNO);

        $element_select = new XoopsFormSelect(_PROFILE_AM_TYPE, 'field_type', $field->getVar('field_type', 'e'));
        $element_select->addOptionArray($fieldtypes);

        $form->addElement($element_select);

        switch ($field->getVar('field_type')) {
            case "textbox":
                $valuetypes = array(XOBJ_DTYPE_ARRAY => _PROFILE_AM_ARRAY,
                XOBJ_DTYPE_EMAIL => _PROFILE_AM_EMAIL,
                XOBJ_DTYPE_INT => _PROFILE_AM_INT,
                XOBJ_DTYPE_TXTAREA => _PROFILE_AM_TXTAREA,
                XOBJ_DTYPE_TXTBOX => _PROFILE_AM_TXTBOX,
                XOBJ_DTYPE_URL => _PROFILE_AM_URL,
                XOBJ_DTYPE_OTHER => _PROFILE_AM_OTHER);
                $type_select = new XoopsFormSelect(_PROFILE_AM_VALUETYPE, 'field_valuetype', $field->getVar('field_valuetype', 'e'));
                $type_select->addOptionArray($valuetypes);
                $form->addElement($valuetypes);
                break;

            case "select":
            case "radio":
                $valuetypes = array(XOBJ_DTYPE_ARRAY => _PROFILE_AM_ARRAY,
                XOBJ_DTYPE_EMAIL => _PROFILE_AM_EMAIL,
                XOBJ_DTYPE_INT => _PROFILE_AM_INT,
                XOBJ_DTYPE_TXTAREA => _PROFILE_AM_TXTAREA,
                XOBJ_DTYPE_TXTBOX => _PROFILE_AM_TXTBOX,
                XOBJ_DTYPE_URL => _PROFILE_AM_URL,
                XOBJ_DTYPE_OTHER => _PROFILE_AM_OTHER);
                $type_select = new XoopsFormSelect(_PROFILE_AM_VALUETYPE, 'field_valuetype', $field->getVar('field_valuetype', 'e'));
                $type_select->addOptionArray($valuetypes);
                $form->addElement($valuetypes);
                break;


        }

        //$form->addElement(new XoopsFormRadioYN(_PROFILE_AM_NOTNULL, 'field_notnull', $field->getVar('field_notnull', 'e')));

        if ($field->getVar('field_type') == "select" || $field->getVar('field_type') == "select_multi" || $field->getVar('field_type') == "radio" || $field->getVar('field_type') == "checkbox") {
            if (count($field->getVar('field_options')) > 0) {
                $remove_options = new XoopsFormCheckBox(_PROFILE_AM_REMOVEOPTIONS, 'removeOptions');
                $options = $field->getVar('field_options');
                asort($options);
                $remove_options->addOptionArray($options);
                $form->addElement($remove_options);
            }

            $option_tray = new XoopsFormElementTray(_PROFILE_AM_ADDOPTION);
            $option_tray->addElement(new XoopsFormText(_PROFILE_AM_KEY, 'addOption[key]', 15, 35));
            $option_tray->addElement(new XoopsFormText(_PROFILE_AM_VALUE, 'addOption[value]', 35, 255));
            $form->addElement($option_tray);
        }
    }

    if ($field->getVar('field_edit')) {
        switch ($field->getVar('field_type')) {
            case "textbox":
                //proceed to next cases
            case "textarea":
            case "dhtml":
                $form->addElement(new XoopsFormText(_PROFILE_AM_MAXLENGTH, 'field_maxlength', 35, 35, $field->getVar('field_maxlength', 'e')));
                $form->addElement(new XoopsFormTextArea(_PROFILE_AM_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
                break;

            case "checkbox":
            case "select_multi":
                $def_value = $field->getVar('field_default', 'e') != null ? unserialize($field->getVar('field_default', 'n')) : null;
                $element = new XoopsFormSelect(_PROFILE_AM_DEFAULT, 'field_default', $def_value, 8, true);
                $options = $field->getVar('field_options');
                asort($options);
                $element->addOptionArray($options);
                $form->addElement($element);
                break;

            case "select":
            case "radio":
                $def_value = $field->getVar('field_default', 'e') != null ? $field->getVar('field_default') : null;
                $element = new XoopsFormSelect(_PROFILE_AM_DEFAULT, 'field_default', $def_value);
                $options = $field->getVar('field_options');
                asort($options);
                $element->addOptionArray($options);
                $form->addElement($element);
                break;

            case "date":
            case "longdate":
                $form->addElement(new XoopsFormTextDateSelect(_PROFILE_AM_DEFAULT, 'field_default', 15, $field->getVar('field_default', 'e')));
                break;

            case "datetime":
                $form->addElement(new XoopsFormDateTime(_PROFILE_AM_DEFAULT, 'field_default', 15, $field->getVar('field_default', 'e')));
                break;

            case "yesno":
                $form->addElement(new XoopsFormRadioYN(_PROFILE_AM_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
                break;

            case "timezone":
                $form->addElement(new XoopsFormSelectTimezone(_PROFILE_AM_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
                break;
                
/*            case "country":
                $form->addElement(new XoopsFormSelectCountry(_PROFILE_AM_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
                break;*/

            case "language":
                $form->addElement(new XoopsFormSelectLang(_PROFILE_AM_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
                break;

            case "group":
                $form->addElement(new XoopsFormSelectGroup(_PROFILE_AM_DEFAULT, 'field_default', true, $field->getVar('field_default', 'e')));
                break;

            case "group_multi":
                $form->addElement(new XoopsFormSelectGroup(_PROFILE_AM_DEFAULT, 'field_default', true, $field->getVar('field_default', 'e'), 5, true));
                break;

            case "theme":
                $form->addElement(new XoopsFormSelectTheme(_PROFILE_AM_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
                break;

            case "autotext":
                $form->addElement(new XoopsFormTextArea(_PROFILE_AM_DEFAULT, 'field_default', $field->getVar('field_default', 'e')));
                break;

            case "image":
                $options = $field->getVar('field_options');
                if ($options == array()) {
                    $options = array('maxwidth' => 200,
                                     'maxheight' => 200,
                                     'maxsize' => 1024);
                }
                $form->addElement(new XoopsFormText(_PROFILE_AM_MAXWIDTH, 'field_maxwidth', 10, 10, $options['maxwidth']));
                $form->addElement(new XoopsFormText(_PROFILE_AM_MAXHEIGHT, 'field_maxheight', 10, 10, $options['maxheight']));
                $form->addElement(new XoopsFormText(_PROFILE_AM_MAXSIZE, 'field_maxsize', 10, 10, $options['maxsize']));
                break;
        }
    }

    $groupperm_handler =& xoops_gethandler('groupperm');
    $searchable_types = array('textbox',
    'select',
    'radio',
    'yesno',
    'date',
    'datetime',
    'timezone',
    'language');
    if (in_array($field->getVar('field_type'), $searchable_types)) {
        $search_groups = $groupperm_handler->getGroupIds('profile_search', $field->getVar('fieldid'), $GLOBALS['xoopsModule']->getVar('mid'));
        $form->addElement(new XoopsFormSelectGroup(_PROFILE_AM_PROF_SEARCH, 'profile_search', true, $search_groups, 5, true));
    }
    if ($field->getVar('field_edit')) {
        //$form->addElement(new XoopsFormText(_PROFILE_AM_FIELD." "._PROFILE_AM_WEIGHT, 'field_weight', 35, 35, $field->getVar('field_weight', 'e')));
        if (!$field->isNew()) {
            //Load groups
            $editable_groups = $groupperm_handler->getGroupIds('profile_edit', $field->getVar('fieldid'), $GLOBALS['xoopsModule']->getVar('mid'));

        }
        else {
            $editable_groups = array();
        }
         $form->addElement(new XoopsFormRadioYN(_PROFILE_AM_EXPORTABLE, 'exportable', $field->getVar('exportable', 'e')));

        $form->addElement(new XoopsFormSelectGroup(_PROFILE_AM_PROF_EDITABLE, 'profile_edit', false, $editable_groups, 5, true));
        $form->addElement(new XoopsFormRadioYN(_PROFILE_AM_REQUIRED, 'field_required', $field->getVar('field_required', 'e')));
        $regstep_select = new XoopsFormSelect(_PROFILE_AM_PROF_REGISTER, 'step_id', $field->getVar('step_id', 'e'));
        $regstep_select->addOption(0, _NO);
        $regstep_handler = icms_getmodulehandler( 'regstep', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
        $regstep_select->addOptionArray($regstep_handler->getList());
        $form->addElement($regstep_select);
    }
    $form->addElement(new XoopsFormHidden('op', 'save'));
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

    return $form;
}
/**
* Get {@link XoopsThemeForm} for registering new users
*
* @param object $user {@link XoopsUser} to register
* @param int $step Which step we are at
* @param ProfileRegstep $next_step
*
* @return object
*/
function getRegisterForm(&$user, $profile, $next_step = 0, $step) {
    $action = $_SERVER['REQUEST_URI'];
    global $icmsConfigUser;
    include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";
	include_once ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/class/forms/profile_form.php";
    $reg_form = new ProfileForm($step->getVar('step_name'), "regform", $action, "post");

    if ($step->getVar('step_intro') != "") {
        $reg_form->addElement(new XoopsFormLabel('', $step->getVar('step_intro')));
    }

    if ($next_step == 0) {
        $uname_size = $icmsConfigUser['maxuname'] < 75 ? $icmsConfigUser['maxuname'] : 75;

        $elements[0][] = array('element' => new XoopsFormText(_PROFILE_MA_USERLOGINNAME."", "login_name", $uname_size, 75, $user->getVar('login_name', 'e')), 'required' => true);
        $weights[0][] = 0;

        $elements[0][] = array('element' => new XoopsFormText(_PROFILE_MA_USERNAME."", "uname", $uname_size, 75, $user->getVar('uname', 'e')), 'required' => true);
        $weights[0][] = 0;

        $elements[0][] = array('element' => new XoopsFormText(_PROFILE_MA_EMAIL, "email", $uname_size, 60, $user->getVar('email', 'e')), 'required' => true);
        $weights[0][] = 0;

        $elements[0][] = array('element' => new XoopsFormPassword(_PROFILE_MA_PASSWORD, "pass", 10, 32, "", false, ($icmsConfigUser['pass_level']?'password_adv':'')), 'required' => true);
        $weights[0][] = 0;
        
        $elements[0][] = array('element' => new XoopsFormPassword(_PROFILE_MA_VERIFYPASS, "vpass", 10, 32, ""), 'required' => true);
        $weights[0][] = 0;
    }

    // Dynamic fields
    $profile_handler =& icms_getmodulehandler( 'profile', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
    // Get fields
    $fields =& $profile_handler->loadFields();

    foreach (array_keys($fields) as $i) {
// MPB ADD - START
// Set field persistance - load profile with session vars
        $fieldname = $fields[$i]->getVar('field_name');
        if (!empty($_SESSION['profile'][$fieldname]) && $value = $_SESSION['profile'][$fieldname]) {
            $profile->setVar($fieldname,$value);
        }
// MPB ADD - END
        if ($fields[$i]->getVar('step_id') == $step->getVar('step_id')) {
            $fieldinfo['element'] = $fields[$i]->getEditElement($user, $profile);
            $fieldinfo['required'] = $fields[$i]->getVar('field_required');

            $key = $fields[$i]->getVar('catid');
            $elements[$key][] = $fieldinfo;
            $weights[$key][] = $fields[$i]->getVar('field_weight');
        }
    }
    ksort($elements);

    // Get categories
    $cat_handler = icms_getmodulehandler( 'category', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
    $categories = $cat_handler->getObjects(null, true, false);

    foreach (array_keys($elements) as $k) {
        array_multisort($weights[$k], SORT_ASC, array_keys($elements[$k]), SORT_ASC, $elements[$k]);
        $title = isset($categories[$k]) ? $categories[$k]['cat_title'] : _PROFILE_MA_DEFAULT;
        $desc = isset($categories[$k]) ? $categories[$k]['cat_description'] : "";
          $reg_form->insertBreak($title, 'head');
        $reg_form->addElement(new XoopsFormLabel("<h2>".$title."</h2>", $desc), false);
        foreach (array_keys($elements[$k]) as $i) {
            $reg_form->addElement($elements[$k][$i]['element'], $elements[$k][$i]['required']);
        }
    }
    //end of Dynamic User fields

    if ($next_step == 0 && $icmsConfigUser['reg_dispdsclmr'] != 0 && $icmsConfigUser['reg_disclaimer'] != '') {
        $disc_tray = new XoopsFormElementTray(_PROFILE_MA_DISCLAIMER, '<br />');
        $disc_text = new XoopsFormLabel("", "<div id=\"disclaimer\">".$GLOBALS["myts"]->displayTarea($icmsConfigUser['reg_disclaimer'],1)."</div>");
        $disc_tray->addElement($disc_text);
        $session_agreement = empty($_SESSION['profile']['agree_disc']) ? '':$_SESSION['profile']['agree_disc'];
        $agree_chk = new XoopsFormCheckBox('', 'agree_disc', $session_agreement);
        $agree_chk->addOption(1, _PROFILE_MA_IAGREE);
        $disc_tray->addElement($agree_chk);
        $reg_form->addElement($disc_tray);
    }
	if ($next_step == 0 && $icmsConfigUser['use_captcha'] == 1) {
	$reg_form->addElement(new IcmsFormCaptcha(_SECURITYIMAGE_GETCODE, "scode"));
	}
    $reg_form->addElement(new XoopsFormHidden("op", "step"));
    $reg_form->addElement(new XoopsFormHidden("step", $next_step));
    $reg_form->addElement(new XoopsFormButton("", "submit", _PROFILE_MA_SUBMIT, "submit"));
    //var_dump($reg_form);
    return $reg_form;
}

/**
* Get {@link XoopsSimpleForm} for finishing registration
*
* @param object $user {@link XoopsUser} object to finish registering
* @param string $vpass Password verification field
* @param mixed $action URL to submit to or false for $_SERVER['REQUEST_URI']
*
* @return object
*/
function getFinishForm(&$user, $vpass, $action = false) {
    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
    include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";

    $form = new XoopsSimpleForm("", "userinfo", $action, "post");
    $profile = $user->getProfile();
    $array = array_merge(array_keys($user->getVars()), array_keys($profile->getVars()));
    foreach ($array as $field) {
        $value = $user->getVar($field, 'e');
        if (is_array($value)) {
            foreach ($value as $thisvalue) {
                $form->addElement(new XoopsFormHidden($field."[]", $thisvalue));
            }
        }
        else {
            $form->addElement(new XoopsFormHidden($field, $value));
        }
    }
    $form->setExtra("", true);
    $myts =& MyTextSanitizer::getInstance();
    $form->addElement(new XoopsFormHidden('vpass', $myts->htmlSpecialChars($vpass)));
    $form->addElement(new XoopsFormHidden('op', 'finish'));
    $form->addElement(new XoopsFormButton('', 'submit', _PROFILE_MA_FINISH, 'submit'));
    return $form;
}

/**
* Get {@link XoopsThemeForm} for editing a user
*
* @param object $user {@link XoopsUser} to edit
*
* @return object
*/
function getUserForm(&$user, $profile = false, $action = false) {
    global $icmsConfig, $icmsModule, $icmsUser, $icmsConfigUser;
    if ($action === false) {
        $action = $_SERVER['REQUEST_URI'];
    }
    include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";
    $title = $user->isNew() ? _PROFILE_AM_ADDUSER : _PROFILE_MA_EDITPROFILE;

    $form = new XoopsThemeForm($title, 'userinfo', $action, 'post', true);

    $profile_handler =& icms_getmodulehandler( 'profile', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
    // Dynamic fields
    if (!$profile) {
        $profile_handler = icms_getmodulehandler( 'profile', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
        $profile = $profile_handler->get($user->getVar('uid'));
    }
    // Get fields
    $fields =& $profile_handler->loadFields();
    // Get ids of fields that can be edited
    $gperm_handler =& xoops_gethandler('groupperm');
    $editable_fields =& $gperm_handler->getItemIds('profile_edit', $icmsUser->getGroups(), $icmsModule->getVar('mid'));

    $email_tray = new XoopsFormElementTray(_PROFILE_MA_EMAIL, '<br />');
    if ($user->isNew() || $icmsUser->isAdmin()) {
    $elements[0][] = array('element' => new XoopsFormText(_PROFILE_MA_USERLOGINNAME, 'login_name', 25, 75, $user->getVar('login_name', 'e')), 'required' => 1);
    $weights[0][] = 0;
    $elements[0][] = array('element' => new XoopsFormText(_PROFILE_MA_USERNAME, 'uname', 25, 75, $user->getVar('uname', 'e')), 'required' => 1);
        $email_text = new XoopsFormText('', 'email', 30, 60, $user->getVar('email'));
    } else {
   	$elements[0][] = array('element' => new XoopsFormLabel(_PROFILE_MA_USERLOGINNAME, $user->getVar('login_name', 'e')), 'required' => 0);
    $weights[0][] = 0;
    if ($icmsConfigUser['allow_chguname'] == 1) {
        $elements[0][] = array('element' => new XoopsFormText(_PROFILE_MA_USERNAME, 'uname', 25, 75, $user->getVar('uname', 'e')), 'required' => 1);
    } else {
        $elements[0][] = array('element' => new XoopsFormLabel(_PROFILE_MA_USERNAME, $user->getVar('uname')), 'required' => 0);
    }
        $email_text = new XoopsFormLabel('', $user->getVar('email'));
    }
    // Weight for uname element
    $weights[0][] = 0;
    $email_tray->addElement($email_text, true);
    $elements[0][] = array('element' => $email_tray, 'required' => 0);
    // Weight for email element
    $weights[0][] = 0;


    if ($icmsUser->isAdmin() && $user->getVar('uid') != $icmsUser->getVar('uid')) {
        //If the user is an admin and is editing someone else
        $pwd_text = new XoopsFormPassword('', 'password', 10, 32, "", false, ($icmsConfigUser['pass_level']?'password_adv':''));
        $pwd_text2 = new XoopsFormPassword('', 'vpass', 10, 32);
        $pwd_tray = new XoopsFormElementTray(_PROFILE_MA_PASSWORD.'<br />'._PROFILE_MA_TYPEPASSTWICE);
        $pwd_tray->addElement($pwd_text,true);
        $pwd_tray->addElement($pwd_text2,true);
        $elements[0][] = array('element' => $pwd_tray, 'required' => 1); //cannot set an element tray required
        $weights[0][] = 0;

        $level_radio = new XoopsFormRadio(_PROFILE_MA_ACTIVEUSER, 'level', $user->getVar('level'));
        $level_radio->addOption(1, _PROFILE_MA_ACTIVE);
        $level_radio->addOption(0, _PROFILE_MA_INACTIVE);
        $level_radio->addOption(-1, _PROFILE_MA_DISABLED);
        $elements[0][] = array('element' => $level_radio, 'required' => 0);
        $weights[0][] = 0;
    }

    $elements[0][] = array('element' => new XoopsFormHidden('uid', $user->getVar('uid')), 'required' => 0);
    $weights[0][] = 0;
    $elements[0][] = array('element' => new XoopsFormHidden('op', 'save'), 'required' => 0);
    $weights[0][] = 0;

    $profile_cat_handler =& icms_getmodulehandler( 'category', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
    /* @var $profile_cat_handler ProfileCategoryHandler */

    $categories =& $profile_cat_handler->getObjects(null, true, false);

    foreach (array_keys($fields) as $i) {
        if (in_array($fields[$i]->getVar('fieldid'), $editable_fields)) {
            $fieldinfo['element'] = $fields[$i]->getEditElement($user, $profile);
            $fieldinfo['required'] = $fields[$i]->getVar('field_required');

            $key = $fields[$i]->getVar('catid');
            $elements[$key][] = $fieldinfo;
            $weights[$key][] = $fields[$i]->getVar('field_weight');

            // Image upload
            if ($fields[$i]->getVar('field_type') == "image") {
                $form->setExtra('enctype="multipart/form-data"');
            }
        }
    }

    if ($icmsUser && $icmsUser->isAdmin()) {
        if (@!include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/".$icmsConfig['language']."/admin.php")) {
            include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/language/english/admin.php");
        }
        $gperm_handler =& xoops_gethandler('groupperm');
        //If user has admin rights on groups
        include_once ICMS_ROOT_PATH."/modules/system/constants.php";
        if ($gperm_handler->checkRight("system_admin", XOOPS_SYSTEM_GROUP, $icmsUser->getGroups(), 1)) {
            //add group selection
            $group_select = new XoopsFormSelectGroup(_PROFILE_AM_GROUP, 'groups', false, $user->getGroups(), 5, true);
            $elements[0][] = array('element' => $group_select, 'required' => 0);
            $weights[0][] = 15000;
        }
    }

    ksort($elements);
    foreach (array_keys($elements) as $k) {
        array_multisort($weights[$k], SORT_ASC, array_keys($elements[$k]), SORT_ASC, $elements[$k]);
        $title = isset($categories[$k]) ? $categories[$k]['cat_title'] : _PROFILE_MA_DEFAULT;
        $desc = isset($categories[$k]) ? $categories[$k]['cat_description'] : "";
        $form->addElement(new XoopsFormLabel("<h2>".$title."</h2>", $desc), false);
        foreach (array_keys($elements[$k]) as $i) {
            $form->addElement($elements[$k][$i]['element'], $elements[$k][$i]['required']);
        }
    }

    $form->addElement(new XoopsFormButton('', 'submit', _PROFILE_MA_SAVECHANGES, 'submit'));
    return $form;
}
?>