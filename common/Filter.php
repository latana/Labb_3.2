<?php

namespace common;

class Filter {
	/**
	 * trim and strip tags from string
	 * 
	 * @param  String
	 * @return String
	 */
	public static function sanitizeString($string) {
		$original = $string;
		$string = ltrim($string);
		$string = rtrim($string);
		$string = strip_tags($string);
		return $string;
	}
	
	/**
	 * 
	 * @param  String
	 * @return boolean
	 */
	public static function hasTags($string) {
		$sanitized = strip_tags($string);
		
		if ($sanitized != $string) {
			return true;
		}
		return false;
	}
}
