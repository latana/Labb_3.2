<?php

namespace login\model;

/**
 * Information about the logged in user
 */
class LoginInfo {
	/**
	 * @var UserCredentials
	 */
	public $user;

	/**
	 * @var String
	 */
	public $ipAdress;

	/**
	 * @var String
	 */
	public $userAgent;

	
	/**
	 * Only called at login and then saved in session
	 * @param UserCredentials $user      logged in user
	 */
	public function __construct(UserCredentials $user) {
		$this->user = $user;
		$this->ipAdress = $ipAdress = $_SERVER["REMOTE_ADDR"];
		$this->userAgent = $userAgent = $_SERVER["HTTP_USER_AGENT"];
	}

	/**
	 * Is it the same session as we logged into
	 * 
	 * @param  String $ipAdress  
	 * @param  String $userAgent 
	 * @return Boolean           
	 */
	public function isSameSession() {
		if($this->ipAdress != $_SERVER["REMOTE_ADDR"] || 
		   $this->userAgent != $_SERVER["HTTP_USER_AGENT"]) {

			\Debug::log("wrong ip or adress, session hijacking? $ipAdress $userAgent");
			$time = time();
			error_log("Session hijacking attempt at $time, $ipAdress $userAgent", 0, "log.txt");
			return false;
		}

		return true;
	}
}