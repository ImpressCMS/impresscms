<?php
/**
 * Class to easily export data from IcmsPersistables
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	marcan <marcan@impresscms.org>
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package	ICMS\IPF\Export
 */
class icms_ipf_export_Handler {

    /**
     * Handler that can provide data
     *
     * @var \icms_ipf_Handler 
     */
	public $handler;
        
        /**
         * criteria how to filter exported data
         *
         * @var \icms_db_criteria_Element
         */
	public $criteria;
        
        /**
         * Fields to be exported. If FALSE then all fields will be exported
         *
         * @var array|bool 
         */
	public $fields = false;
        
        /**
         * Format of the ouputed export. Currently only supports CSV
         *
         * @var string
         */
	public $format = 'csv';
        
        /**
         * Name of the file to be created
         *
         * @var string
         */
	public $filename = '';
        
        /**
         * Path where the file will be saved
         *
         * @var string 
         */
	public $filepath = '';
        
        /**
         * Options of the format to be exported in
         *
         * @var array 
         */
	public $options = array();
        
        /**
         * Output methods used for formating exported data
         *
         * @var bool|array
         */
	public $outputMethods=false;
        
        /**
         * Fields data should not be included in generated result
         *
         * @var array 
         */
	public $notDisplayFields = array();

	/**
	 * Constructor
	 *
	 * @param \icms_ipf_Handler         $objectHandler  IcmsPersistableHandler handling the data we want to export
	 * @param \icms_db_criteria_Element $criteria       Containing the criteria of the query fetching the objects to be exported
	 * @param array|false               $fields         Fields to be exported. If FALSE then all fields will be exported
	 * @param string                    $filename       Name of the file to be created
	 * @param string                    $filepath       Path where the file will be saved
	 * @param string                    $format         Format of the ouputed export. Currently only supports CSV
	 * @param array                     $options        Options of the format to be exported in
	 */
	public function __construct(&$objectHandler, $criteria=null, $fields=false, $filename=false, $filepath=false, $format='csv', $options=false) {
		$this->handler = $objectHandler;
		$this->criteria = $criteria;
		$this->fields = $fields;
		$this->filename = $filename;
		$this->format = $format;
		$this->options = $options;
	}

	/**
	 * Renders the export
	 */
	public function render($filename) {

		$this->filename = $filename;

		$objects = $this->handler->getObjects($this->criteria);
		$rows = array();
		$columnsHeaders = array();
		$firstObject = true;
		foreach ( $objects as $object) {
			$row = array();
			foreach ( $object->getVars() as $key=>$var) {
				if ((!$this->fields || in_array($key, $this->fields)) && !in_array($key, $this->notDisplayFields)) {
					if ($this->outputMethods && (isset($this->outputMethods[$key])) && (method_exists($object, $this->outputMethods[$key]))) {
						$method = $this->outputMethods[$key];
						$row[$key] = $object->$method();
					} else {
						$row[$key] = $object->getVar($key);
					}
					if ($firstObject) {
						// then set the columnsHeaders array as well
						$columnsHeaders[$key] = $var['form_caption'];
					}
				}
			}
			$firstObject = false;
			$rows[] = $row;
			unset($row);
		}
		$data = array();
		$data['rows'] = $rows;
		$data['columnsHeaders'] = $columnsHeaders;
		$smartExportRenderer = new icms_ipf_export_Renderer($data, $this->filename, $this->filepath, $this->format, $this->options);
		$smartExportRenderer->execute();
	}

	/**
	 * Set an array contaning the alternate methods to use instead of the default getVar()
	 *
	 * @param 	array	$outputMethods array example : 'uid' => 'getUserName'...
	 */
	public function setOuptutMethods($outputMethods) {
		$this->outputMethods = $outputMethods;
	}

	/*
	 * Set an array of fields that we don't want in export
	 *
	 * @param	str|array	$fields
	 */
	public function setNotDisplayFields($fields) {
		if (!$this->notDisplayFields) {
			if (is_array($fields)) {
				$this->notDisplayFields = $fields;
			} else {
				$this->notDisplayFields = array($fields);
			}
		} else {
			if (is_array($fields)) {
				$this->notDisplayFields = array_merge($this->notDisplayFields, $fields);
			} else {
				$this->notDisplayFields[] = $fields;
			}
		}
	}
}

