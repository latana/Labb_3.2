<?php

namespace login\model;

require_once("UserCredentials.php");
require_once("UserList.php");
require_once("LoginInfo.php");


class LoginModel {
	/**
	 * Location in $_SESSION
	 * @var string
	 */
	private static $loggedInUser = "LoginModel::loggedInUser";
	
	/**
	 * @var \model\UserList
	 */
	private $allUsers;
	
	
	public function __construct() {
		assert(isset($_SESSION));
		
		$this->allUsers = new UserList();
	}
	
	/**
	 * @param  UserCredentials $fromClient
	 * @param  LoginObserver   $observer 
	 *
	 * @throws  \Exception if login failed
	 */
	public function doLogin(UserCredentials $fromClient, 
							LoginObserver $observer) {

		try {
			$validUser = $this->allUsers->findUser($fromClient);

			//create new temporary password and save it
			$validUser->newTemporaryPassword();

			//this user needs to be saved since temporary password changed
			$this->allUsers->update($validUser);

			$this->setLoggedIn($validUser, $observer);

			$observer->loginOK($validUser->getTemporaryPassword());
		} catch (\Exception $e) {
			\Debug::log("Login Failed", false, $e->getMessage());
			$observer->LoginFailed();
			throw $e;
		}
	}

	/**
	 * @return boolean [description]
	 */
	public function isLoggedIn() {
		if (isset($_SESSION[self::$loggedInUser])) {

			if ($_SESSION[self::$loggedInUser]->isSameSession()) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * @return UserCredentials 
	 */
	public function getLoggedInUser() {
		return $_SESSION[self::$loggedInUser]->user;
	}
	

	public function doLogout() {
		unset($_SESSION[self::$loggedInUser]);
	}
	
	/**
	 * @param UserCredentials $info [description]
	 */
	private function setLoggedIn(UserCredentials $info) {
		$_SESSION[self::$loggedInUser] = new LoginInfo($info);
	}
}
