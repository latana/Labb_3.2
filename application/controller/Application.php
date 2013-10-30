<?php

namespace application\controller;

require_once("application/view/View.php");
require_once("login/controller/LoginController.php");


/**
 * Main application controller
 */
class Application {
	/**
	 * \view\view
	 * @var [type]
	 */
	private $view;

	/**
	 * @var \login\controller\LoginController
	 */
	private $loginController;
	
	public function __construct() {
		$loginView = new \login\view\LoginView();
		
		$this->loginController = new \login\controller\LoginController($loginView);
		$this->view = new \application\view\View($loginView);
	}
	
	/**
	 * @return \common\view\Page
	 */
	public function doFrontPage() {
		
		$this->loginController->doToggleLogin();
	
		if ($this->loginController->isLoggedIn()) {
			$loggedInUserCredentials = $this->loginController->getLoggedInUser();
			return $this->view->getLoggedInPage($loggedInUserCredentials);	
		} 
		
		else {
			return $this->view->getLoggedOutPage();
			
			if($this->view->userWantToCreatNew){
				$this->view->showRegisterBox();
			}
		}
	}
}
