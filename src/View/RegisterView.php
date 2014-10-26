<?php

class RegisterView
{
	private $registerModel;
	private $feedbackMessage = "";

	public function __construct(RegisterModel $registerModel) 
	{
		$this->registerModel = $registerModel;
	}

	public function GenerateRegisterHTML()
	{
		$usernameInput = $this->GetUsernameInput();

		//Filtrera ogiltiga tecken
		$usernameInput = preg_replace('/[^A-Za-z0-9-_!åäöÅÄÖ]/', '', $usernameInput);

		$HTML = "<h2>Laborationskod hk222gn</h2>
				<a href='?login'>Tillbaka</a>
				<form name='f1' method='post' action='?register'>
					<h3>Användarnamn</h3>
					<input type='text' name='username' value='$usernameInput'>
					<h3>Lösenord</h3>
					<input type='password' name='password'>
					<h3>Repetera Lösenord</h3>
					<input type='password' name='passwordCheck'>
					<h3>Skicka:</h3>
					<input type='submit' value='Registera' name='doRegister'>
				</form>
				";

		$feedbackMsg = $this->GetFeedbackMessage();

		if ($feedbackMsg != "") 
		{
			$HTML .= $feedbackMsg;
		}

		return $HTML;
	}

	public function SetFeedbackMessage($msg)
	{
		$this->feedbackMessage = $msg;
	}

	public function GetFeedbackMessage()
	{
		return $this->feedbackMessage;
	}

	public function GetUsernameInput()
	{
		if (isset($_POST['username'])) 
		{ 
			return $_POST['username'];
		}
		return false;
	}

	public function GetPasswordInput()
	{
		if (isset($_POST['password'])) 
		{ 
			return $_POST['password'];
		}
		return false;
	}

	public function GetPasswordCheckInput()
	{
		if (isset($_POST['passwordCheck'])) 
		{ 
			return $_POST['passwordCheck'];
		}
		return false;
	}

	public function IsOnRegisterPage()
	{
		if (isset($_GET['register']))
		{
			return true;
		}
		return false;
	}

	public function SetLoginURLAttribute()
	{
		if ($this->IsOnRegisterPage())
		{
			$params = $_GET;
			$params['register'] = "login";
			$paramString = http_build_query($params);
		}
	}

	public function DidUserClickRegister()
	{
		if (isset($_POST["doRegister"]))
			return true;
		return false;
	}
}