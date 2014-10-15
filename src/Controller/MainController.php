<?php

require_once("Controller/AccountController.php");
require_once("Controller/GameController.php");
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
		$this->loginModel = new LoginModel();
		$this->loginView = new LoginView($this->loginModel);
		$this->accountController = new AccountController($this->loginModel, $this->loginView);
		$this->gameController = new GameController();
	}

	public function Run()
	{
		$body = $this->accountController->HandleAccounts();


		//Är man inloggad så kallas accountController->Logout? Annars körs spelet, inloggning/registrering är som vanligt.
		if ($this->loginModel->IsLoggedIn($this->loginView->GetUserAgent(), $this->loginView->GetUserIP()))
		{
			$body = $this->gameController->HandleGame($this->loginModel->GetUsername()) . $body;
		}
		
		
		return $body;
	}

}
