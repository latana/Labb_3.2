<?php

require_once("common/model/DebugItem.php");
//This has no namespace for convenience, it should really be a common module

class Debug {

	/**
	 * @var array of \common\model\DebugItem
	 */
	private static $debugItems = array();
	

	/**
	 * @param String  $string The debug message from 
	 * @param boolean $trace  include a debug_backtrace?
	 * @param mixed  $object include object
	 */
	public static function log($string, $trace = false, $object = null) {
		self::$debugItems[] = new \common\model\DebugItem($string, $trace, $object);
	}
	
	/**
	 * @return array of \common\model\DebugItem
	 */
	public static function getList() {
		return self::$debugItems;
	}
}
