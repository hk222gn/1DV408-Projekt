<?php

require_once("model/RegisterModel.php");
require_once("view/RegisterView.php");

//TODO:
//The url attribute is still ?register even tho the login page is showing. Can't really fix that with header("Location: ?login"); because it's redirecting so the success message gets removed. @//HERE

class AccountController
{
	private $view;
	private $model;
	private $registerView;
	private $registerModel;

	public function __construct($model, $view)
	{
		$this->model = $model;
		$this->view = $view;
		$this->registerModel = new RegisterModel();
		$this->registerView = new RegisterView($this->registerModel);
	}

	public function HandleAccounts()
	{
		if ($this->registerView->IsOnRegisterPage())
		{
			if ($this->registerView->DidUserClickRegister())
			{
				$feedback = $this->registerModel->RegisterUser($this->registerView->GetUsernameInput(), $this->registerView->GetPasswordInput(), $this->registerView->GetPasswordCheckInput());

				$this->registerView->SetFeedbackMessage($feedback);
			}

			if ($this->registerModel->DidRegistrationSucceed())
			{
				$this->view->SetFeedbackMessage($feedback);
				//HERE
			}
			else
			{
				return $this->registerView->GenerateRegisterHTML();
			}
		}
		else
		{
			if ($this->model->IsLoggedIn($this->view->GetUserAgent(), $this->view->GetUserIP())) 
			{
				//Checks if "doLogout" is sent in the post, if it is and the user is actually logged in, log the user out...
				if ($this->view->DidUserRequestLogout())
				{
					$feedback = $this->model->LogOut();
					$this->view->UnsetUserCookies();
					$this->view->SetFeedbackMessage($feedback);
				}
			}
			else
			{
				//...then we check if the user requested to login....
				if ($this->view->DidUserRequestLogin())
				{
					$feedback = $this->model->Login($this->view->GetUsernameInput(), $this->view->GetPasswordInput());

					if ($this->model->GetLoginStatus())
					{
						$this->model->SaveUserSpecificInformation($this->view->GetUserAgent(), $this->view->GetUserIP());
						
						if ($this->view->RememberMe())
						{
							//Create a one time use password for the cookie.
							$name = $this->view->GetUsernameInput();
							$tempPW = $this->model->CreateOneTimePassword($name);

							//Save in cookie
							$feedback = $this->view->SaveUserCookie($name, $tempPW);
						}
					}
					$this->view->SetFeedbackmessage($feedback);
				}

				//...if he didn't press the login button but he has saved cridentials, log him in using cookies.
				if ($this->view->AreCookiesSet() && !$this->view->DidUserRequestLogin()) 
				{
					$feedback = $this->model->Login($this->view->GetUsernameCookie(), $this->view->GetPasswordCookie(), true);

					if (!$this->model->GetLoginStatus())
						$this->view->UnsetUserCookies();
					else
						$this->model->SaveUserSpecificInformation($this->view->GetUserAgent(), $this->view->GetUserIP());

					$this->view->SetFeedbackmessage($feedback);
				}
			}
		}
		return $this->view->GenerateHTML($this->model->IsLoggedIn($this->view->GetUserAgent(), $this->view->GetUserIP()));
	}
}