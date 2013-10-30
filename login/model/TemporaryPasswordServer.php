<?php

namespace login\model;

/**
 * The server representation of a temporary password
 * adds expireDate 
 */
class TemporaryPasswordServer extends TemporaryPassword {
	
	/**
	 * @var String
	 */
	protected $temporaryPassword;
	
	/**
	 * @var int Unix Timestamp
	 */
	private $expireDate;
	
	
	public function __construct() {
		$this->expireDate = time() + 60*60*24*30; //one month from now
		$this->temporaryPassword = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 40);
	}

	/**
	 * @return int Unix Timestamp
	 */
	public function getExpireDate() {
		return $this->expireDate;
	}

	/**
	 * Serialize object to string
	 * @return String
	 */
	public function toString() {
		return "$this->temporaryPassword><$this->expireDate";
	}
	
	/**
	 * @param  String $serverString
	 * @return TemporaryPasswordServer
	 */
	public static function fromString($serverString) {
		$ret = new TemporaryPasswordServer();
		$parts = explode("><", $serverString);
		$ret->temporaryPassword = $parts[0];
		$ret->expireDate = $parts[1];
		return $ret;
	}
		
	/**
	 * @param  TemporaryPasswordClient $fromCookie
	 * @return boolean 
	 */
	public function doMatch(TemporaryPasswordClient $fromCookie) {
		
		if (time() > $this->expireDate) {
			\Debug::log("cookie expired ");
			return false;
		}
		
		if (strcmp($this->temporaryPassword, $fromCookie->temporaryPassword) != 0){
			\Debug::log("passwords not matching [$this->temporaryPassword] && [$fromCookie->temporaryPassword]");
			return false;
		}
		
		return true;
	}
}
