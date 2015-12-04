<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class IPFTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
            'icms_ipf_Tree' => null,
            'icms_ipf_Object' => ['icms_core_Object'],            
            'icms_ipf_Metagen' => null,
            'icms_ipf_Highlighter' => null,
            'icms_ipf_Handler' => ['icms_core_ObjectHandler'],
            'icms_ipf_Controller' => null,
            'icms_ipf_About' => null,
            'icms_ipf_view_Column' => null,
            'icms_ipf_view_Row' => null,
            'icms_ipf_view_Single' => null,
            'icms_ipf_view_Table' => null,
            'icms_ipf_view_Tree' => ['icms_ipf_view_Table'],                        
            'icms_ipf_seo_Object' => ['icms_ipf_Object'],
            'icms_ipf_registry_Handler' => null,
            'icms_ipf_permission_Handler' => null,
            'icms_ipf_member_Handler' => ['icms_member_Handler'],
            'icms_ipf_form_Base' => ['icms_form_Theme'],
            'icms_ipf_form_Secure' => ['icms_ipf_form_Base'],
            'icms_ipf_form_elements_Yesno' => ['icms_form_elements_Radioyn'],
            'icms_ipf_form_elements_User' => ['icms_form_elements_Select'],
            'icms_ipf_form_elements_Urllink' => ['icms_form_elements_Tray'],
            'icms_ipf_form_elements_Upload' => ['icms_form_elements_File'],
            'icms_ipf_form_elements_Time' => ['icms_form_elements_Select'],
            'icms_ipf_form_elements_Text' => ['icms_form_elements_Text'],
            'icms_ipf_form_elements_Source' => ['icms_form_elements_Textarea'],
            'icms_ipf_form_elements_Signature' => ['icms_form_elements_Tray'],
            'icms_ipf_form_elements_Select' => ['icms_form_elements_Select'],
            'icms_ipf_form_elements_Selectmulti' => ['icms_ipf_form_elements_Select'],
            'icms_ipf_form_elements_Section' => ['icms_form_Element'],
            'icms_ipf_form_elements_Richfile' => ['icms_form_elements_Tray'],
            'icms_ipf_form_elements_Radio' => ['icms_form_elements_Radio'],
            'icms_ipf_form_elements_Passwordtray' => ['icms_form_elements_Tray'],
            'icms_ipf_form_elements_Parentcategory' => ['icms_form_elements_Select'],
            'icms_ipf_form_elements_Page' => ['icms_form_elements_Tray'],
            'icms_ipf_form_elements_Language' => ['icms_form_elements_select_Lang'],
            'icms_ipf_form_elements_Imageupload' => ['icms_ipf_form_elements_Upload'],
            'icms_ipf_form_elements_Image' => ['icms_form_elements_Tray'],
            'icms_ipf_form_elements_Fileupload' => ['icms_ipf_form_elements_Upload'],
            'icms_ipf_form_elements_File' => ['icms_form_elements_File'],
            'icms_ipf_form_elements_Datetime' => ['icms_form_elements_Datetime'],
            'icms_ipf_form_elements_Date' => ['icms_form_elements_Date'],
            'icms_ipf_form_elements_Checkbox' => ['icms_form_elements_Checkbox'],
            'icms_ipf_form_elements_Blockoptions' => ['icms_form_elements_Tray'],
            'icms_ipf_form_elements_Autocomplete' => ['icms_form_elements_Text'],
            'icms_ipf_export_Renderer' => null,
            'icms_ipf_export_Handler' => null,
            'icms_ipf_category_Object' => ['icms_ipf_seo_Object'],
            'icms_ipf_category_Handler' => ['icms_ipf_Handler']
        ] as $class => $must_be_instances_of) {
            $this->assertTrue(class_exists($class, true), $class . ' does\'t exist');
            if ($must_be_instances_of === null) {
                continue;
            }
            $instance = $this->getClassInstance($class);
            foreach ($must_be_instances_of as $must_be_instance_of) {
                $this->assertTrue($instance instanceof $must_be_instance_of, $class . ' must be instance of ' . $must_be_instance_of . ' but is not');
            }
        }
    }
    
    /**
     * Gets instance of class from classname
     * 
     * @param string $class     ClassName
     * 
     * @return object
     */
    private function getClassInstance($class) {
        $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        return $instance;
    }

    /**
     * Test methods availability
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_ipf_Tree' => [
                'getTree',
                'getByKey',
                'getFirstChild',
                'getAllChild',
                'getAllParent',
                'makeSelBox'
            ],
            'icms_ipf_Object' => [
                'isLoadedOnCreation',
                'accessGranted',
                'openFormSection',
                'closeFormSection',
                'getVarInfo',
                'getFormCaption',
                'getFormDescription',
                'initVar',
                'initNonPersistableVar',
                'quickInitVar',
                'updateMetas',
                'setControl',
                'getControl',
                'getForm',
                'getSecureForm',
                'isPartial',
                'toArray',
                'setFieldAsRequired',
                'setFieldForSorting',
                'setImageDir',
                'getGroupPerm',
                'getImageDir',
                'getUploadDir',
                'id',
                'title',
                'summary',
                'getAdminViewItemLink',
                'getItemLink',
                'getViewItemLink',
                'getEditItemLink',
                'getDeleteItemLink',
                'getPrintAndMailLink',
                'getFieldsForSorting',
                'store',
                'getVarsForSQL',
                'getValueFor',
                'doMakeFieldreadOnly',
                'getCriteriaForSelectByID',
                'makeFieldReadOnly',
                'doHideFieldFromForm',
                'doHideFieldFromSingleView',
                'hideFieldFromForm',
                'hideFieldFromSingleView',
                'doShowFieldOnForm',
                'displaySingleObject',
                'doDisplayFieldOnSingleView',
                'doSetFieldAsRequired',
                'doSetFieldForSorting',
                'showFieldOnForm',
                'delete',
                'displayFieldOnSingleView',
                'doSetAdvancedFormFields',
                'setAdvancedFormFields',
                'getUrlLinkObj',
                'storeUrlLinkObj',
                'getFileObj',
                'storeFileObj',
                'serialize',
                'unserialize'
            ],
            'icms_ipf_Metagen' => [
                'emptyString',
                'generateSeoTitle',
                'html2text',
                'setTitle',
                'setKeywords',
                'setCategoryPath',
                'setDescription',
                'createTitleTag',
                'purifyText',
                'createMetaDescription',
                'findMetaKeywords',
                'createMetaKeywords',
                'autoBuildMeta_keywords',
                'buildAutoMetaTags',
                'createMetaTags'
            ],
            'icms_ipf_Handler' => [
                'addEventHook',
                'addPermission',
                'setGrantedObjectsCriteria',
                'create',
                'getImageUrl',
                'getImagePath',
                'get',
                'getObjects',
                'getCalculatedInfo',
                'query',
                'getObjectsD',
                'getD',
                'getListD',
                'insertD',
                'insert',
                'getObjectsAsArray',
                'doFastChange',
                'getSkipKeys',
                'convertResultSet',
                'getList',
                'getCount',
                'delete',
                'disableEvent',
                'getIdsFromObjectsAsArray',
                'getPermissions',
                'save',
                'updateAll',
                'deleteAll',
                'getModuleInfo',
                'getModuleConfig',
                'getModuleItemString',
                'updateCounter',
                'executeEvent',
                'getIdentifierName',
                'enableUpload',
                'setUploaderConfig'
            ],
            'icms_ipf_Controller' => [
                'postDataToObject',
                'doStoreFromDefaultForm',
                'storeFromDefaultForm',
                'storeicms_ipf_ObjectD',
                'storeicms_ipf_Object',
                'handleObjectDeletion',
                'handleObjectDeletionFromUserSide',
                'getAdminViewItemLink',
                'getItemLink',
                'getViewItemLink',
                'getEditLanguageLink',
                'getEditItemLink',
                'getDeleteItemLink',
                'getPrintAndMailLink',
                'getModuleItemString'
            ],
            'icms_ipf_About' => [
                'sanitize',
                'render'
            ],
            'icms_ipf_view_Tree' => [
                'getChildrenOf',
                'createTableRow',
                'createTableRows',
                'fetchObjects'
            ],
            'icms_ipf_view_Table' => [
                'addActionButton',
                'addColumn',
                'addIntroButton',
                'addPrinterFriendlyLink',                
                'addQuickSearch',
                'addHeader',
                'addFooter',
                'addDefaultIntroButton',
                'addCustomAction',
                'setDefaultSort',
                'getDefaultSort',
                'setDefaultOrder',
                'getDefaultOrder',
                'addWithSelectedActions',
                'addFilter',
                'setDefaultFilter',
                'isForUserSide',
                'setCustomTemplate',
                'setSortOrder',
                'setTableId',
                'setObjects',
                'createTableRows',
                'fetchObjects',
                'getDefaultFilter',
                'getFiltersArray',
                'setDefaultFilter2',
                'getDefaultFilter2',
                'getFilters2Array',
                'renderOptionSelection',
                'getLimitsArray',
                'getObjects',
                'hideActionColumnTitle',
                'hideFilterAndLimit',
                'getOrdersArray',
                'renderD',
                'renderForPrint',
                'render',
                'disableColumnsSorting',
                'fetch'
            ],
            'icms_ipf_view_Single' => [
                'addRow',
                'render',
                'fetch'
            ],
            'icms_ipf_view_Row' => [
                'getKeyName',
                'isHeader'
            ],
            'icms_ipf_view_Column' => [
                'getKeyName',
                'getAlign',
                'isSortable',
                'getWidth',
                'getCustomCaption'
            ],
            'icms_ipf_seo_Object' => [
                'initiateSEO',
                'short_url',
                'meta_keywords',
                'meta_description'
            ],            
            'icms_ipf_registry_Handler' => [
                'addObjectsFromHandler',
                'addListFromHandler',
                'addObjectsFromItemName',
                'addListFromItemName',
                'getObjects',
                'getList',
                'getSingleObject'               
            ],
            'icms_ipf_permission_Handler' => [
                'getGrantedGroups',
                'getGrantedGroupsForIds',
                'getGrantedItems',
                'storeAllPermissionsForId',
                'saveItem_Permissions',
                'accessGranted'
            ],
            'icms_ipf_member_Handler' => [
                'genUserNames',
                'genRandNumber',
                'initRand'
            ],
            'icms_ipf_form_Base' => [
                'addCustomButton',
                'addElement',
                'getElementById',
                'render',
                'assign',
                'renderValidationJS',
                'renderValidationJS2'
            ],
            'icms_ipf_form_elements_Section' => [
                'getValue',
                'isClosingSection',
                'render'
            ],
            'icms_ipf_form_elements_Checkbox' => [
                'renderValidationJS'
            ],
            'icms_ipf_export_Renderer' => [
                'arrayToCsvString',
                'valToCsvHelper',
                'execute',
                'saveExportFile',
                'saveCsv'                
            ],
            'icms_ipf_export_Handler' => [
                'render',
                'setOuptutMethods',
                'setNotDisplayFields'
            ],
            'icms_ipf_category_Object' => [
                'description',
                'image',
                'toArray',
                'getCategoryPath'                
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), 'Static method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }
    
    /**
     * Test static method availability
     */
    public function testStaticMethodsAvailability() {
        foreach ([
            'icms_ipf_registry_Handler' => [
                'getInstance'
            ]
        ] as $class => $methods) {
            $instance = $this->getClassInstance($class);
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($instance, $method), 'Method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }    
    
    /**
     * Tests variables availability and types
     */
    public function testVariables() {
        foreach ([
            'icms_ipf_Tree' => [
                '_myId' => 'string',
                '_tree'    => 'array'
            ],
            'icms_ipf_Object' => [
                '_image_path' => 'string',
                '_image_url' => 'string',
                'seoEnabled' => 'bool',
                'titleField' => 'null',
                'summaryField' => 'bool',
                'handler' => 'null',
                'controls' => 'array'
            ],
            'icms_ipf_Metagen' => [
                '_myts' => 'null',
                '_title' => 'string',
                '_original_title' => 'string',
                '_keywords' => 'string',
                '_meta_description' => 'string',
                '_categoryPath' => 'string',
                '_description' => 'string',
                '_minChar' => 'int'
            ],
            'icms_ipf_Highlighter' => [
                'content' => 'string'
            ],
            'icms_ipf_Handler' => [
                '_itemname' => 'string',
                'table' => 'string',
                'keyName' => 'string',
                'className' => 'string',
                'identifierName' => 'string',
                'summaryName' => 'string',
                '_page' => 'string',
                '_modulePath' => 'string',
                '_moduleUrl' => 'string',
                '_moduleName' => 'string',
                'uploadEnabled' => 'bool',
                '_uploadUrl' => 'string',
                '_uploadPath' => 'string',
                '_allowedMimeTypes' => 'array',
                '_maxFileSize' => 'int',
                '_maxWidth' => 'int',
                '_maxHeight' => 'int',
                'highlightFields' => 'array',
                'visibleColumns' => 'array',
                'eventArray' => 'array',
                'permissionsArray' => 'array',
                'generalSQL' => 'string',
                '_eventHooks' => 'array',
                '_disabledEvents' => 'array',
                'debugMode' => 'bool'
            ],
            'icms_ipf_Controller' => [
                'handler' => 'null'
            ],
            'icms_ipf_About' => [
                '_lang_aboutTitle' => 'string',
                '_lang_author_info' => 'string',
                '_lang_developer_lead' => 'string',
                '_lang_developer_contributor' => 'string',
                '_lang_developer_website' => 'string',
                '_lang_developer_email' => 'string',
                '_lang_developer_credits' => 'string',
                '_lang_module_info' => 'string',
                '_lang_module_status' => 'string',
                '_lang_module_release_date' => 'string',
                '_lang_module_demo' => 'string',
                '_lang_module_support' => 'string',
                '_lang_module_bug' => 'string',
                '_lang_module_submit_bug' => 'string',
                '_lang_module_feature' => 'string',
                '_lang_module_submit_feature' => 'string',
                '_lang_module_disclaimer' => 'string',
                '_lang_author_word' => 'string',
                '_lang_version_history' => 'string',
                '_lang_by' => 'string',
                '_tpl' => 'null'       
            ],           
            'icms_ipf_view_Table' => [
                '_id' => 'string',
                '_objectHandler' => 'string',
                '_columns' => 'string',
                '_criteria' => 'string',
                '_actions' => 'string',
                '_objects = false' => 'string',
                '_aObjects' => 'string',
                '_custom_actions' => 'string',
                '_sortsel' => 'string',
                '_ordersel' => 'string',
                '_limitsel' => 'string',
                '_filtersel' => 'string',
                '_filterseloptions' => 'string',
                '_filtersel2' => 'string',
                '_filtersel2options' => 'string',
                '_filtersel2optionsDefault' => 'string',
                '_tempObject' => 'string',
                '_tpl' => 'string',
                '_introButtons' => 'string',
                '_quickSearch' => 'bool',
                '_actionButtons' => 'array',
                '_head_css_class' => 'string',
                '_hasActions' => 'bool',
                '_userSide' => 'bool',
                '_printerFriendlyPage' => 'bool',
                '_tableHeader = false' => 'string',
                '_tableFooter = false' => 'string',
                '_showActionsColumnTitle' => 'bool',
                '_isTree' => 'bool',
                '_showFilterAndLimit' => 'bool',
                '_enableColumnsSorting' => 'bool',
                '_customTemplate' => 'bool',
                '_withSelectedActions' => 'array'
            ],
            'icms_ipf_view_Single' => [
                '_object' => 'null',
                '_userSide' => 'bool',
                '_tpl' => 'string',
                '_rows' => 'array',
                '_actions' => 'array',
                '_headerAsRow' => 'bool'
            ],
            'icms_ipf_view_Row' => [
                '_keyname' => 'string',
                '_align' => 'string',
                '_customMethodForValue' => 'null',
                '_header' => 'array',
                '_class' => 'string'
            ],
            'icms_ipf_view_Column' => [
                '_keyname' => 'string',
                '_align' => 'string',
                '_width' => 'int',
                '_customMethodForValue' => 'null',
                '_extraParams' => 'string',
                '_sortable' => 'bool',
                '_customCaption' => 'string'
            ],
            'icms_ipf_permission_Handler' => [
                'handler' => 'null'
            ],
            'icms_ipf_form_Base' => [
                'targetObject' => 'null',
                'form_fields' => 'null'
            ],
            'icms_ipf_export_Renderer' => [
                'data' => 'array',
                'format' => 'string',
                'filename' => 'string',
                'filepath' => 'string',
                'options' => 'array'
            ],
            'icms_ipf_export_Handler' => [
                'handler' => 'string',
                'criteria' => 'string',
                'fields' => 'array',
                'format' => 'string',
                'filename' => 'string',
                'filepath' => 'string',
                'options' => 'array',
                'outputMethods' => 'array',
                'notDisplayFields' => 'array'
            ],
            'icms_ipf_category_Handler' => [
                'allCategoriesObj' => 'bool'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $type) {
                $this->assertInternalType($type, $instance->$variable, '$' . $variable . ' is not of type ' . $type . ' in instance of ' . $class);
            }
        }
    }    
    
}