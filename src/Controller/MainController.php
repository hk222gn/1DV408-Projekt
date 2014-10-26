<?php

require_once("Controller/AccountController.php");
require_once("Controller/GameController.php");
require_once("model/DAL/UserRepository.php");
require_once("model/LoginModel.php");
require_once("view/LoginView.php");

class MainController
{
	private $accountController;
	private $gameController;
	private $loginModel;
	private $loginView;

	public function __construct()
	{
		$userRepo = new UserRepository();
		$this->loginModel = new LoginModel($userRepo);
		$this->loginView = new LoginView($this->loginModel);
		$this->accountController = new AccountController($this->loginModel, $this->loginView, $userRepo);
		$this->gameController = new GameController();
	}

	public function Run()
	{
		$body = $this->accountController->HandleAccounts();

		if ($this->loginModel->IsLoggedIn($this->loginView->GetUserAgent(), $this->loginView->GetUserIP()))
		{
			$body = $this->gameController->HandleGame($this->loginModel->GetUsername()) . $body;
		}
		
		return $body;
	}

}
