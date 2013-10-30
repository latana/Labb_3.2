<?php

namespace login\model;

class Password {
	const MINIMUM_PASSWORD_CHARACTERS = 6;
	const MAXIMUM_PASSWORD_CHARACTERS = 16;

	/**
	 * @var String
	 */
	private $encryptedPassword;

	private function __construct() {

	}

	/**
	 * Create password from encrypted String
	 * @param  String $encryptedPassword
	 * @return Password
	 */
	public static function fromEncryptedString($encryptedPassword) {
		$ret = new Password();
		$ret->encryptedPassword = $encryptedPassword;
		return $ret;	
		
	}

	/**
	 * Create password from cleartext string
	 * @param  String $password
	 * @return Password
	 */
	public static function fromCleartext($cleartext) {
		if (self::isOkPassword($cleartext) == true ) {
			$ret = new Password();
			$ret->encryptedPassword = $ret->encryptPassword($cleartext, "salty salt");
			return $ret;
		} 
		throw new \Exception("Tried to create user with faulty password");
	}

	/**
	 * Create empty/nonvalid password 
	 * @return Password
	 */
	public static function emptyPassword() {
		return new Password();
	}

	/**
	 * @return String
	 */
	public function __toString() {
		return $this->encryptedPassword;
	}

	

	/**
	 * @param  String  $string 
	 * @return boolean         
	 */
	private static function isOkPassword($string) {
		
		if (strlen($string) < self::MINIMUM_PASSWORD_CHARACTERS) {
			return false;
		} else if (strlen($string) > self::MAXIMUM_PASSWORD_CHARACTERS) {
			return false;
		}
		return true;
	}
	
	/**
	 * @param  String $rawPassword 
	 * @return String              
	 */
	private function encryptPassword($rawPassword, $salt) {
		return sha1($rawPassword . "Password_SALT");
	}
}