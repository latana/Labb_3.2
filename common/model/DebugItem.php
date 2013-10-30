<?php

namespace common\model;

/**
 * @todo Maybe add some information hiding!
 */
class DebugItem {

	/**
	 * @var String
	 */
	public $item;

	/**
	 * @var mixed, depends on what the user supplies
	 */
	public $object;

	/**
	 * @var array see debug_backtrace function 
	 */
	public $debugBacktrace;

	/**
	 * @var String filepath
	 */
	public $calledFromFile;
	
	
	/**
	 * [__construct description]
	 * @param String  $string The debug message from 
	 * @param boolean $trace  include a debug_backtrace?
	 * @param mixed  $object include object
	 */
	public function __construct($string, $trace = false, $object = null) {
		$this->item = $string;
		if ($object != null)
			$this->object = var_export($object, true);
		
		$this->debugBacktrace = debug_backtrace();
		$this->calledFromFile = $this->cleanFilePath($this->debugBacktrace[1]["file"]);
		if (!$trace) {
			$this->debugBacktrace = null;
		}
		
	}
	
	/**
	 * @param  String $path FilePath
	 * @return String 
	 * @todo  remove project path...
	 */
	private function cleanFilePath($path) {
		//$_SERVER[SCRIPT_FILENAME]
		return $path;
	}
	 
}