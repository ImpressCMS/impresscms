<?php
/**
 * Contains the basis classes for managing any objects derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

icms_loadLanguageFile('system', 'common');

/**
 * icms_ipf_Object base class
 *
 * Base class representing a single icms_ipf_Object
 *
 * @package	ICMS\IPF
 * @author      marcan <marcan@smartfactory.ca>
 * @todo	Properly identify and declare the visibility of vars and functions
 */
class icms_ipf_Object extends icms_core_Object {

    public $_image_path;
    public $_image_url;
    public $seoEnabled = false;
    public $titleField;
    public $summaryField = false;

    /**
     * Reference to the handler managing this object
     *
     * @var icms_ipf_Handler
     */
    public $handler;

    /**
     * References to control objects, managing the form fields of this object
     */
    public $controls = array();
    
    /**
     * Does object data loaded on creation?
     *
     * @var bool 
     */
    private $_loadedOnCreation = false;

    public function __construct(&$handler, $data = array()) {
        $this->handler = $handler;        
        if ($data === true) {
            $this->setNew();
        } elseif (!empty($data)) {            
            $this->assignVars($data);
            $this->_loadedOnCreation = true;
        }
    }
    
    /**
     * Does object data loaded on creation?
     * 
     * @return bool
     */
    public function isLoadedOnCreation() {
        return $this->_loadedOnCreation;
    }

    /**
     * Checks if the user has a specific access on this object
     *
     * @param string $gperm_name name of the permission to test
     * @return boolean : TRUE if user has access, false if not
     * */
    public function accessGranted($perm_name) {
        $icmspermissions_handler = new icms_ipf_permission_Handler($this->handler);
        return $icmspermissions_handler->accessGranted($perm_name, $this->id());
    }

    /**
     * open a new form section to seperate form elements
     *
     * @param	str		$section_name
     * @param	bool	$value
     */
    public function openFormSection($section_name, $value = FALSE) {
        $this->initVar($section_name, self::DTYPE_FORM_SECTION, $value, FALSE, NULL, '', FALSE, '', '', FALSE, FALSE, TRUE);
    }

    /**
     * close a form section
     *
     * @param	str		$section_name
     */
    public function closeFormSection($section_name) {
        $this->initVar('close_section_' . $section_name, self::DTYPE_FORM_SECTION_CLOSE, '', FALSE, NULL, '', FALSE, '', '', FALSE, FALSE, TRUE);
    }   
    
    public function getVarInfo($key = null, $info = null, $default = null) {
        if ($key == null) {
            $ret = parent::getVarInfo($key, $info, $default);
            foreach ($ret as $key => $value) {
                $ret[$key]['form_caption'] = $this->getFormCaption($key, $value['form_caption']);
                $ret[$key]['form_dsc'] = $this->getFormDescription($key, $value['form_dsc']);
            }
            return $ret;
        } else {
            $ret = parent::getVarInfo($key, $info, $default);
            if ($info == null) {
                $ret['form_caption'] = $this->getFormCaption($key, isset($ret['form_caption'])?$ret['form_caption']:null);
                $ret['form_dsc'] = $this->getFormDescription($key, isset($ret['form_dsc'])?$ret['form_dsc']:null);
            } else {
                switch ($info) {
                    case 'form_caption':
                        $ret = $this->getFormCaption($key, $ret);
                        break;
                    case 'form_dsc':
                        $ret = $this->getFormDescription($key, $ret);
                        break;
                }
            }
            return $ret;
        }
    }

    protected function getFormCaption($key, $form_caption) {
        if ($form_caption)
            return $form_caption;
        $dyn_form_caption = strtoupper('_CO_' . $this->handler->_moduleName . '_' . $this->handler->_itemname . '_' . $key);            
        if (defined($dyn_form_caption))
            $form_caption = constant($dyn_form_caption);
        else
            $form_caption = $dyn_form_caption;
        parent::setVarInfo($key, 'form_caption', $form_caption);
        return $form_caption;
    }
    
    protected function getFormDescription($key, $form_dsc) {
        if ($form_dsc) {
            return $form_dsc;
        }
        $dyn_form_dsc = strtoupper('_CO_' . $this->handler->_moduleName . '_' . $this->handler->_itemname . '_' . $key);            
        if (defined($dyn_form_dsc)) {
            $form_dsc = constant($dyn_form_dsc);
        } else {
            $form_dsc = $dyn_form_dsc;
        }
        parent::setVarInfo($key, 'form_dsc', $form_dsc);
        return $form_dsc;
    }

    /**
     *
     * @param string $key key of this field. This needs to be the name of the field in the related database table
     * @param int $data_type  set to one of self::DTYPE_XXX constants
     * @param mixed $value default value of this variable
     * @param bool $required set to TRUE if this variable needs to have a value set before storing the object in the table
     * @param int $maxlength maximum length of this variable, for self::DTYPE_STRING and self::DTYPE_INTEGER types only
     * @param string $options does this data have any select options?
     * @param bool $multilingual is this field needs to support multilingual features (NOT YET IMPLEMENTED...)
     * @param string $form_caption caption of this variable in a {@link icms_ipf_form_Base} and title of a column in a  {@link icms_ipf_ObjectTable}
     * @param string $form_dsc description of this variable in a {@link icms_ipf_form_Base}
     * @param bool $sortby set to TRUE to make this field used to sort objects in icms_ipf_ObjectTable
     * @param bool $persistent set to FALSE if this field is not to be saved in the database
     * @param bool $displayOnForm to be displayed on the form or not
     */
    public function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '', $multilingual = false, $form_caption = '', $form_dsc = '', $sortby = false, $persistent = true, $displayOnForm = true) {
        //url_ is reserved for files.
        if (substr($key, 0, 4) == 'url_')
            return trigger_error("Cannot use variable starting with 'url_'.");
        
        parent::initVar($key, $data_type, $value, $required, $maxlength, $options);
                
        $this->_vars[$key]['multilingual'] = $multilingual;
        $this->_vars[$key]['form_caption'] = $form_caption;
        $this->_vars[$key]['form_dsc'] = $form_dsc;
        $this->_vars[$key]['sortby'] = $sortby;
        $this->_vars[$key]['persistent'] = $persistent;
        $this->_vars[$key]['displayOnForm'] = $displayOnForm;
        $this->_vars[$key]['displayOnSingleView'] = true;
        $this->_vars[$key]['readonly'] = false;
    }

    /**
     *
     *
     * @param			$key
     * @param			$data_type
     * @param	str		$itemName
     * @param	str 	$form_caption
     * @param			$sortby
     * @param			$value
     * @param	bool	$displayOnForm
     * @param	bool	$required
     */
    public function initNonPersistableVar($key, $data_type, $itemName = false, $form_caption = '', $sortby = false, $value = '', $displayOnForm = false, $required = false) {
        $this->initVar($key, $data_type, $value, $required, null, '', false, $form_caption, '', $sortby, false, $displayOnForm);
        $this->_vars[$key]['displayOnSingleView'] = false;
        $this->_vars[$key]['itemName'] = $itemName;
    }

    /**
     * Quickly initiate a var
     *
     * Since many vars do have the same config, let's use this method with some of these configuration as a convention ;-)
     *
     * - $maxlength = 0 unless $data_type is a TEXTBOX, then $maxlength will be 255
     * - all other vars are NULL or '' depending of the parameter
     * 
     * @deprecated since version 2.1
     *
     * @param string $key key of this field. This needs to be the name of the field in the related database table
     * @param int $data_type  set to one of XOBJ_DTYPE_XXX constants (set to self::DTYPE_DEP_OTHER if no data type ckecking nor text sanitizing is required)
     * @param bool $required set to TRUE if this variable needs to have a value set before storing the object in the table
     * @param string $form_caption caption of this variable in a {@link icms_ipf_form_Base} and title of a column in a  {@link icms_ipf_ObjectTable}
     * @param string $form_dsc description of this variable in a {@link icms_ipf_form_Base}
     * @param mixed $value default value of this variable
     */
    public function quickInitVar($key, $data_type, $required = false, $form_caption = '', $form_dsc = '', $value = null) {
        icms_core_Debug::setDeprecated('initVar', sprintf(_CORE_REMOVE_IN_VERSION, '2.1'));
        $maxlength = $data_type == self::DTYPE_DEP_TXTBOX ? 255 : null;
        $this->initVar($key, $data_type, $value, $required, $maxlength, '', false, $form_caption, $form_dsc, false, true, true);
    }

    public function updateMetas() {
        // Auto create meta tags if empty
        
        
        $icms_metagen = new icms_ipf_Metagen($this->title(), $this->getVar('meta_keywords'), $this->summary());

        if (!isset($this->meta_keywords) || !isset($this->meta_description)) {

            if (!isset($this->meta_keywords)) {
                $this->setVar('meta_keywords', $icms_metagen->_keywords);
            }

            if (!isset($this->meta_description)) {
                $this->setVar('meta_description', $icms_metagen->_meta_description);
            }
        }

        // Auto create short_url if empty
        if (!isset($this->short_url)) {
            $this->setVar('short_url', $icms_metagen->generateSeoTitle($this->title('n'), false));
        }
    }

    /**
     * Set control information for an instance variable
     *
     * The $options parameter can be a string or an array. Using a string
     * is the quickest way :
     *
     * $this->setControl('date', 'datetime');
     *
     * This will create a date and time selectbox for the 'date' var on the
     * form to edit or create this item.
     *
     * Here are the currently supported controls :
     *
     * 		- color
     * 		- country
     * 		- datetime
     * 		- date
     * 		- email
     * 		- group
     * 		- group_multi
     * 		- image
     * 		- imageupload
     * 		- label
     * 		- language
     * 		- parentcategory
     * 		- password
     * 		- selectmulti
     * 		- select
     * 		- text
     * 		- textarea
     * 		- theme
     * 		- theme_multi
     * 		- timezone
     * 		- user
     * 		- user_multi
     * 		- yesno
     *
     * Now, using an array as $options, you can customize what information to
     * use in the control. For example, if one needs to display a select box for
     * the user to choose the status of an item. We only need to tell icms_ipf_Object
     * what method to execute within what handler to retreive the options of the
     * selectbox.
     *
     * $this->setControl('status', array('name' => false,
     * 	                                 'itemHandler' => 'item',
     *                                   'method' => 'getStatus',
     *                                   'module' => 'smartshop'));
     *
     * In this example, the array elements are the following :
     * 		- name : false, as we don't need to set a special control here.
     * 				 we will use the default control related to the object type (defined in initVar)
     * 		- itemHandler : name of the object for which we will use the handler
     * 		- method : name of the method of this handler that we will execute
     * 		- module : name of the module from wich the handler is
     *
     * So in this example, icms_ipf_Object will create a selectbox for the variable 'status' and it will
     * populate this selectbox with the result from SmartshopItemHandler::getStatus()
     *
     * Another example of the use of $options as an array is for TextArea :
     *
     * $this->setControl('body', array('name' => 'textarea',
     *                                   'form_editor' => 'default'));
     *
     * In this example, icms_ipf_Object will create a TextArea for the variable 'body'. And it will use
     * the 'default' editor, providing it is defined in the module
     * preferences : $icmsModuleConfig['default_editor']
     *
     * Of course, you can force the use of a specific editor :
     *
     * $this->setControl('body', array('name' => 'textarea',
     *                                   'form_editor' => 'koivi'));
     *
     * Here is a list of supported editor :
     * 		- tiny : TinyEditor
     * 		- dhtmltextarea : ImpressCMS DHTML Area
     * 		- fckeditor	: FCKEditor
     * 		- inbetween : InBetween
     * 		- koivi : Koivi
     * 		- spaw : Spaw WYSIWYG Editor
     * 		- htmlarea : HTMLArea
     * 		- textarea : basic textarea with no options
     *
     * @param string $var name of the variable for which we want to set a control
     * @param array $options
     */
    public function setControl($var, $options = array()) {
        if (isset($this->controls[$var])) {
            unset($this->controls[$var]);
        }
        if (is_string($options)) {
            $options = array('name' => $options);
        }
        $this->controls[$var] = $options;
    }

    /**
     * Get control information for an instance variable
     *
     * @param string $var
     */
    public function getControl($var) {
        if (isset($this->controls[$var])) {
            return $this->controls[$var];
        } else {
            switch (isset($this->_vars[$var][self::VARCFG_DEP_DATA_TYPE])?$this->_vars[$var][self::VARCFG_DEP_DATA_TYPE]:$this->_vars[$var][self::VARCFG_TYPE]) {
                case self::DTYPE_BOOLEAN:
                    return array('name' => 'yesno');
                //case self::DTYPE_DEP_CURRENCY:
                case self::DTYPE_DEP_MTIME:
                case self::DTYPE_DATETIME;
                case self::DTYPE_DEP_STIME:
                    return array('name' => 'datetime');
                case self::DTYPE_DEP_TIME_ONLY:
                    return array('name' => 'time');
                case self::DTYPE_DEP_URL:
                case self::DTYPE_DEP_URLLINK:
                    return array('name' => 'urllink');
                case self::DTYPE_DEP_SOURCE:
                    return array('name' => 'source');
                case self::DTYPE_DEP_EMAIL:
                    return array('name' => 'email');
                case self::DTYPE_DEP_TXTBOX:
                    return array('name' => 'text');
                case self::DTYPE_DEP_IMAGE:
                    return array('name' => 'image');
                case self::DTYPE_FILE:
                    return array('name' => 'richfile');
                default:
                    return array('name' => 'text');
            }            
        }
    }

    /**
     * Create the form for this object
     *
     * @return a {@link SmartobjectForm} object for this object
     *
     * @see icms_ipf_ObjectForm::icms_ipf_ObjectForm()
     */
    public function getForm($form_caption, $form_name, $form_action = false, $submit_button_caption = _CO_ICMS_SUBMIT, $cancel_js_action = false, $captcha = false) {
        return new icms_ipf_form_Base($this, $form_name, $form_caption, $form_action, null, $submit_button_caption, $cancel_js_action, $captcha);
    }

    /**
     * Create the secure form for this object
     *
     * @return a {@link icms_ipf_form_Secure} object for this object
     *
     * @see icms_ipf_ObjectForm::icms_ipf_ObjectForm()
     */
    public function getSecureForm($form_caption, $form_name, $form_action = false, $submit_button_caption = _CO_ICMS_SUBMIT, $cancel_js_action = false, $captcha = false) {
        $form = new icms_ipf_form_Secure($this, $form_name, $form_caption, $form_action, null, $submit_button_caption, $cancel_js_action, $captcha);

        return $form;
    }
    
    /**
     * Returns if data for this object has partial data
     * 
     * @return bool
     */
    public function isPartial() {
        return !empty($this->handler->visibleColumns);
    }

    /**
     *
     */
    public function toArray() {
        $ret = parent::toArray();
        if ($this->isPartial()) {
            $ret = array_intersect_key($ret, array_flip($this->handler->visibleColumns));
        }
        if ($this->handler->identifierName != "") {
            $controller = new icms_ipf_Controller($this->handler);
            /**
             * Addition of some automatic value
             */
            $ret['itemLink'] = $controller->getItemLink($this);
            $ret['itemUrl'] = $controller->getItemLink($this, true);
            $ret['editItemLink'] = $controller->getEditItemLink($this, false, true);
            $ret['deleteItemLink'] = $controller->getDeleteItemLink($this, false, true);
            $ret['printAndMailLink'] = $controller->getPrintAndMailLink($this);
        }
        /**
         * @todo implement this in ImpressCMS core
         */
        /*
          // Hightlighting searched words
          include_once SMARTOBJECT_ROOT_PATH . 'class/smarthighlighter.php' ;
          $highlight = icms_getConfig('module_search_highlighter', false, true);

          if ($highlight && isset($_GET['keywords']))
          {
          $myts =& icms_core_Textsanitizer::getInstance();
          $keywords= icms_core_DataFilter::htmlSpecialChars(trim(urldecode($_GET['keywords'])));
          $h= new SmartHighlighter ($keywords, true , 'smart_highlighter');
          foreach ($this->handler->highlightFields as $field) {
          $ret[$field] = $h->highlight($ret[$field]);
          }
          }
         */
        return $ret;
    }

    /**
     *
     *
     * @param $field
     * @param $required
     */
    public function setFieldAsRequired($field, $required = true) {
        if (is_array($field)) {
            foreach ($field as $v) {
                $this->doSetFieldAsRequired($v, $required);
            }
        } else {
            $this->doSetFieldAsRequired($field, $required);
        }
    }

    /**
     *
     *
     * @param $field
     */
    public function setFieldForSorting($field) {
        if (is_array($field)) {
            foreach ($field as $v) {
                $this->doSetFieldForSorting($v);
            }
        } else {
            $this->doSetFieldForSorting($field);
        }
    }

    /**
     *
     *
     * @param $url
     * @param $path
     */
    public function setImageDir($url, $path) {
        $this->_image_url = $url;
        $this->_image_path = $path;
    }

    /**
     * Retreive the group that have been granted access to a specific permission for this object
     *
     * @return string $group_perm name of the permission
     */
    public function getGroupPerm($group_perm) {
        if (!$this->handler->getPermissions()) {
            $this->setError("Trying to access a permission that does not exists for this object's handler");
            return false;
        }

        $icmspermissions_handler = new icms_ipf_permission_Handler($this->handler);
        $ret = $icmspermissions_handler->getGrantedGroups($group_perm, $this->id());

        if (count($ret) == 0) {
            return false;
        } else {
            return $ret;
        }
    }

    /**
     *
     *
     * @param $path
     */
    public function getImageDir($path = false) {
        if ($path) {
            return $this->_image_path;
        } else {
            return $this->_image_url;
        }
    }

    /**
     *
     *
     * @param	str		$path
     */
    public function getUploadDir($path = false) {
        if ($path) {
            return $this->_image_path;
        } else {
            return $this->_image_url;
        }
    }

    /**
     * Get the id of the object
     *
     * @return int id of this object
     */
    public function id() {
        return $this->getVar($this->handler->keyName, 'e');
    }

    /**
     * Return the value of the title field of this object
     *
     * @return string
     */
    public function title($format = 's') {
        return $this->getVar($this->handler->identifierName, $format);
    }

    /**
     * Return the value of the title field of this object
     *
     * @return string
     */
    public function summary() {
        if ($this->handler->summaryName) {
            return $this->getVar($this->handler->summaryName);
        } else {
            return false;
        }
    }

    /**
     * Retreive the object admin side link, displayijng a SingleView page
     *
     * @param bool $onlyUrl wether or not to return a simple URL or a full <a> link
     * @return string user side link to the object
     */
    public function getAdminViewItemLink($onlyUrl = false) {
        $controller = new icms_ipf_Controller($this->handler);
        return $controller->getAdminViewItemLink($this, $onlyUrl);
    }

    /**
     * Retreive the object user side link
     *
     * @param bool $onlyUrl wether or not to return a simple URL or a full <a> link
     * @return string user side link to the object
     */
    public function getItemLink($onlyUrl = false) {
        $controller = new icms_ipf_Controller($this->handler);
        return $controller->getItemLink($this, $onlyUrl);
    }

    /**
     *
     *
     * @param $onlyUrl
     * @param $withimage
     * @param $userSide
     */
    public function getViewItemLink($onlyUrl = false, $withimage = true, $userSide = false) {
        $controller = new icms_ipf_Controller($this->handler);
        return $controller->getViewItemLink($this, $onlyUrl, $withimage, $userSide);
    }

    /**
     *
     *
     * @param	bool	$onlyUrl
     * @param	bool	$withimage
     * @param	bool	$userSide
     */
    public function getEditItemLink($onlyUrl = false, $withimage = true, $userSide = false) {
        $controller = new icms_ipf_Controller($this->handler);
        return $controller->getEditItemLink($this, $onlyUrl, $withimage, $userSide);
    }

    /**
     *
     *
     * @param	bool	$onlyUrl
     * @param	bool	$withimage
     * @param	bool	$userSide
     */
    public function getDeleteItemLink($onlyUrl = false, $withimage = false, $userSide = false) {
        $controller = new icms_ipf_Controller($this->handler);
        return $controller->getDeleteItemLink($this, $onlyUrl, $withimage, $userSide);
    }

    /**
     *
     */
    public function getPrintAndMailLink() {
        $controller = new icms_ipf_Controller($this->handler);
        return $controller->getPrintAndMailLink($this);
    }

    /**
     *
     *
     * @param		$sortsel
     */
    public function getFieldsForSorting($sortsel) {
        $ret = array();

        foreach ($this->_vars as $key => $field_info) {
            if ($field_info['sortby']) {
                $ret[$key]['caption'] = $field_info['form_caption'];
                $ret[$key]['selected'] = $key == $sortsel ? "selected='selected'" : '';
            }
        }

        if (count($ret) > 0) {
            return $ret;
        } else {
            return false;
        }
    }

    /**
     * store object
     *
     * @param bool $force
     * @return bool true if successful, false if not
     */
    public function store($force = false) {        
        return $this->handler->insert($this, $force);
    }
    
    /**
     * Returns array with changed vars
     * 
     * @return array
     */
    public function getVarsForSQL($only_changed) {
        $fieldsToStoreInDB = array();
        
        $db = &$this->handler->db;      
        
        $vars = $only_changed?$this->getChangedVars():array_keys($this->_vars);        
        
        foreach ($vars as $k) {
            if ($this->handler->keyName == $k && !$this->_vars[$k][self::VARCFG_VALUE]) {
                continue; // Skipping ID
            }
            if ($this->_vars[$k]['persistent'] === true || $this->_vars[$k]['persistent'] === null) {
                switch ($this->_vars[$k][self::VARCFG_TYPE]) {
                    case self::DTYPE_FLOAT:                    
                        $fieldsToStoreInDB[$k] = (float)$this->_vars[$k][self::VARCFG_VALUE];
                        break;
                    case self::DTYPE_DATETIME:
                        $fieldsToStoreInDB[$k] = 'FROM_UNIXTIME(' .  intval($this->_vars[$k][self::VARCFG_VALUE]) . ')';
                        break;                    
                    case self::DTYPE_BOOLEAN:
                    case self::DTYPE_INTEGER:
                        $fieldsToStoreInDB[$k] = (int) $this->_vars[$k][self::VARCFG_VALUE];
                        break;
                    case self::DTYPE_ARRAY:
                        $value = json_encode($this->_vars[$k][self::VARCFG_VALUE]);
                        $fieldsToStoreInDB[$k] = $db->quoteString($value);
                        break;                    
                    case self::DTYPE_LIST:
                        $separator = $this->_vars[$k][self::VARCFG_SEPARATOR];
                        if (is_array($this->_vars[$k][self::VARCFG_VALUE]))  {
                            $value = array_map('strval', $this->_vars[$k][self::VARCFG_VALUE]);
                            $value = implode($separator, $value);
                        } else {
                            $value = $this->_vars[$k][self::VARCFG_VALUE];
                        }
                        if (!is_string($value)) {
                            $value = strval($value);
                        }
                        $fieldsToStoreInDB[$k] = $db->quoteString($value);
                        break;
                    default:
                        //var_dump(array($k, $this->getVar($k, 'n')));
                        $fieldsToStoreInDB[$k] = $db->quoteString($this->_vars[$k][self::VARCFG_VALUE]);
                }
            }
        }                
        return $fieldsToStoreInDB;
    }

    /**
     *
     *
     * @param unknown_type $key
     * @param unknown_type $editor
     */
    public function getValueFor($key, $editor = true) {
        global $icmsModuleConfig;

        $ret = $this->getVar($key, 'n');
        $myts = icms_core_Textsanitizer::getInstance();

        $control = isset($this->controls[$key]) ? $this->controls[$key] : false;
        $form_editor = isset($control['form_editor']) ? $control['form_editor'] : 'textarea';

        $html = isset($this->_vars['dohtml']) ? $this->getVar('dohtml') : true;
        $smiley = true;
        $xcode = true;
        $image = true;
        $br = isset($this->_vars['dobr']) ? $this->getVar('dobr') : true;
        $formatML = true;

        if ($form_editor == 'default') {
            global $icmsModuleConfig;
            $form_editor = isset($icmsModuleConfig['default_editor']) ? $icmsModuleConfig['default_editor'] : 'textarea';
        }

        if ($editor) {
            if (defined('XOOPS_EDITOR_IS_HTML') && !(in_array($form_editor, array('formtextarea', 'textarea', 'dhtmltextarea')))) {
                $br = false;
                $formatML = !$editor;
            } else {
                return htmlspecialchars($ret, ENT_QUOTES);
            }
        }

        if (method_exists($myts, 'formatForML')) {
            return $myts->displayTarea($ret, $html, $smiley, $xcode, $image, $br, $formatML);
        } else {
            if ($html) {
                return $myts->displayTarea($ret, $html, $smiley, $xcode, $image, $br);
            } else {
                return icms_core_DataFilter::checkVar($ret, 'text', 'output');
            }
        }
    }

    /**
     *
     *
     * @param	str	$key
     */
    public function doMakeFieldreadOnly($key) {
        if (isset($this->_vars[$key])) {
            $this->_vars[$key]['readonly'] = true;
            $this->_vars[$key]['displayOnForm'] = true;
        }
    }
    
    /**
     * Returns criteria for selecting this element by id
     * 
     * @return \icms_db_criteria_Item
     */
    public function getCriteriaForSelectByID() {        
        $criteria = new icms_db_criteria_Compo();
        if (is_array($this->handler->keyName)) {
            foreach ($this->handler->keyName as $key) 
                $criteria->add(new icms_db_criteria_Item($key, $this->getVar($key)));
        } else
            $criteria->add(new icms_db_criteria_Item($this->handler->keyName, $this->getVar($this->handler->keyName)));
        
        return $criteria;
    }

    /**
     *
     *
     * @param	str|arr	$key
     */
    public function makeFieldReadOnly($key) {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doMakeFieldreadOnly($v);
            }
        } else {
            $this->doMakeFieldreadOnly($key);
        }
    }

    /**
     *
     *
     * @param	str	$key
     */
    public function doHideFieldFromForm($key) {
        $this->setVarInfo($key, 'displayOnForm', false);
    }

    /**
     *
     *
     * @param $key
     */
    public function doHideFieldFromSingleView($key) {
        $this->setVarInfo($key, 'displayOnSingleView', false);
    }

    /**
     *
     *
     * @param $key
     */
    public function hideFieldFromForm($key) {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doHideFieldFromForm($v);
            }
        } else {
            $this->doHideFieldFromForm($key);
        }
    }

    /**
     *
     *
     * @param $key
     */
    public function hideFieldFromSingleView($key) {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doHideFieldFromSingleView($v);
            }
        } else {
            $this->doHideFieldFromSingleView($key);
        }
    }

    /**
     *
     *
     * @param unknown_type $key
     */
    public function doShowFieldOnForm($key) {
        if (isset($this->_vars[$key])) {
            $this->_vars[$key]['displayOnForm'] = true;
        }
    }

    /**
     * Display an automatic SingleView of the object, based on the displayOnSingleView param of each vars
     *
     * @param bool $fetchOnly if set to TRUE, then the content will be return, if set to FALSE, the content will be outputed
     * @param bool $userSide for futur use, to do something different on the user side
     * @return content of the template if $fetchOnly or nothing if !$fetchOnly
     */
    public function displaySingleObject($fetchOnly = false, $userSide = false, $actions = array(), $headerAsRow = true) {
        $singleview = new icms_ipf_view_Single($this, $userSide, $actions, $headerAsRow);
        // add all fields mark as displayOnSingleView except the keyid
        foreach ($this->_vars as $key => $var) {
            if ($key != $this->handler->keyName && $var['displayOnSingleView']) {
                $is_header = ($key == $this->handler->identifierName);
                $singleview->addRow(new icms_ipf_view_Row($key, false, $is_header));
            }
        }

        if ($fetchOnly) {
            $ret = $singleview->render($fetchOnly);
            return $ret;
        } else {
            $singleview->render($fetchOnly);
        }
    }

    /**
     *
     *
     * @param unknown_type $key
     */
    public function doDisplayFieldOnSingleView($key) {
        if (isset($this->_vars[$key])) {
            $this->_vars[$key]['displayOnSingleView'] = true;
        }
    }

    /**
     *
     *
     * @param $field
     * @param $required
     */
    public function doSetFieldAsRequired($field, $required = true) {
        $this->setVarInfo($field, 'required', $required);
    }

    /**
     *
     *
     * @param unknown_type $field
     */
    public function doSetFieldForSorting($field) {
        $this->setVarInfo($field, 'sortby', true);
    }

    /**
     *
     *
     * @param unknown_type $key
     */
    public function showFieldOnForm($key) {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doShowFieldOnForm($v);
            }
        } else {
            $this->doShowFieldOnForm($key);
        }
    }

    /**
     * delete object
     *
     * @param bool $force
     * @return bool true if successful, false if not
     */
    public function delete($force = false) {
        return $this->handler->delete($this, $force);
    }

    /**
     *
     *
     * @param $key
     */
    public function displayFieldOnSingleView($key) {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doDisplayFieldOnSingleView($v);
            }
        } else {
            $this->doDisplayFieldOnSingleView($key);
        }
    }

    /**
     *
     *
     * @param $key
     */
    public function doSetAdvancedFormFields($key) {
        if (isset($this->_vars[$key])) {
            $this->_vars[$key]['advancedform'] = true;
        }
    }

    /**
     *
     * @param <type> $key
     */
    public function setAdvancedFormFields($key) {
        if (is_array($key)) {
            foreach ($key as $v) {
                $this->doSetAdvancedFormFields($v);
            }
        } else {
            $this->doSetAdvancedFormFields($key);
        }
    }

    /**
     * get urllink object
     *
     * @param string $key field name
     * @return icms_data_urllink_Object
     */
    public function getUrlLinkObj($key) {
        $urllink_handler = icms::handler("icms_data_urllink");

        $urllinkid = $this->getVar($key, 'n');
        if ($urllinkid != 0) {
            return $urllink_handler->get($urllinkid);
        } else {
            return $urllink_handler->create();
        }
    }

    /**
     * store urllink object
     *
     * @param icms_data_urllink_Object $urllinkObj
     * @return bool
     */
    public function storeUrlLinkObj($urllinkObj) {
        $urllink_handler = icms::handler("icms_data_urllink");
        return $urllink_handler->insert($urllinkObj);
    }

    /**
     * store file object
     *
     * @param string $key field name
     * @return icms_data_file_Object
     */
    function getFileObj($key) {
        $file_handler = icms::handler("icms_data_file");
        $fileid = $this->getVar($key) != null ? $this->getVar($key) : 0;
        if ($fileid != 0) {
            return $file_handler->get($fileid);
        } else {
            return $file_handler->create();
        }
    }

    /**
     * store file object
     *
     * @param icms_data_file_Object $fileObj
     * @return bool
     */
    function storeFileObj($fileObj) {
        $file_handler = icms::handler("icms_data_file");
        return $file_handler->insert($fileObj);
    }
    
    public function serialize() {        
         $data = array('vars' => parent::getValues(), 
                       'handler' => array(
                           'module' => $this->handler->_moduleName,
                           'itemname' => $this->handler->_itemname
                       ));
         return serialize($data);
     }
    
    public function unserialize($serialized) {
        $data = unserialize($serialized);
        if ($data['handler']['module'] == 'core' || $data['handler']['module'] == 'icms') {
            $handler = icms::handler('icms_' . $data['handler']['itemname']);       
        } else {
            $handler = icms_getModuleHandler($data['handler']['itemname'], $data['handler']['module']);
        }
        $this->__construct($handler, $data['vars']);
    }   

}