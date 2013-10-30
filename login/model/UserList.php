<?php

namespace login\model;


require_once("UserCredentials.php");
require_once("common/model/PHPFileStorage.php");
// require_once("../../register/Register.php");

/**
 * represents All users in the system
 *

 */
class UserList {
	/**
	 * Temporary solution with only one user "Admin" in PHPFileStorage
 	 * You might want to use a database instead.
	 * @var \common\model\PHPFileStorage
	 */
	private $adminFile;

	/**
	 * We only have one user in the system right now.
	 * @var array of UserCredentials
	 */
	private $users;


	public function  __construct( ) {
		$this->users = array();
		
		$this->loadAdmin();
	}

	/**
	 * Do we have this user in this list?
	 * @throws  Exception if user provided is not in list
	 * @param  UserCredentials $fromClient
	 * @return UserCredentials from list
	 */
	public function findUser(UserCredentials $fromClient) {
		foreach($this->users as $user) {
			if ($user->isSame($fromClient) ) {
				\Debug::log("found User");
				return  $user;
			}
		}
		throw new \Exception("could not login, no matching user");
	}

	public function update(UserCredentials $changedUser) {
		//this user needs to be saved since temporary password changed
		$this->adminFile->writeItem($changedUser->getUserName(), $changedUser->toString());

		\Debug::log("wrote changed user to file", true, $changedUser);
		$this->users[$changedUser->getUserName()->__toString()] = $changedUser;
	}

	/**
	 * Temporary function to store "Admin" user in file "data/admin.php"
	 * If no file is found a new one is created.
	 * 
	 * @return [type] [description]
	 */
	private function loadAdmin() {
		
		$this->adminFile = new \common\model\PHPFileStorage("data/admin.php");
		try {
			//Read admin from file
			$adminUserString = $this->adminFile->readItem("Admin");
			$admin = UserCredentials::fromString($adminUserString);

		} catch (\Exception $e) {
			\Debug::log("Could not read file, creating new one", true, $e);

			//Create a new user
			$userName = new UserName("Admin");
			$password = Password::fromCleartext("Password");
			$admin = UserCredentials::create( $userName, $password);
			$this->update($admin);
		}
		
		$connMyBase = mysqli_connect("latana.se.mysql", "latana_se", "123456", "latana_se");

			if (mysqli_connect_errno()){
				
				$this->message = "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
			$result = mysqli_query($connMyBase,"SELECT * FROM Users");
			
				while($row = mysqli_fetch_array($result)){
	
					$myBaseUser = $row[0];
					$myBasePass = $row[1];
					
					$userName = new UserName($myBaseUser);
					$password = Password::fromCleartext($myBasePass);
					$admin = UserCredentials::create( $userName, $password);
					$this->update($admin);
					
				}
				
			$connMyBase->close();
		
		$this->users[$admin->getUserName()->__toString()] = $admin;
	}
	
	private function GetMyUsers(){
			
			
	}
	
	
}