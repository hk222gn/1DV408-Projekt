<?php

class LoginView
{
	private $model;
	private $feedbackMessage = "";
	private static $usernameFieldAndCookieName = "username";
	private static $passwordFieldAndCookieName = "password";

	public function __construct(LoginModel $model)
	{
		$this->model = $model;
	}

	public function GetUserAgent()
	{
		return $_SERVER['HTTP_USER_AGENT'];
	}

	public function GetUserIP()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	public function GetUsernameInput()
	{
		if (isset($_POST[self::$usernameFieldAndCookieName])) 
		{ 
			return $_POST[self::$usernameFieldAndCookieName];
		}
		return false;
	}

	public function GetPasswordInput()
	{
		if (isset($_POST[self::$passwordFieldAndCookieName])) 
		{ 
			return $_POST[self::$passwordFieldAndCookieName];
		}
		return false;
	}

	public function DidUserRequestLogout()
	{
		if (isset($_POST['doLogout']))
		{
			return true;
		}
		return false;
	}

	public function DidUserRequestLogin()
	{
		if (isset($_POST['doLogin'])) 
		{
			return true;
		}
		return false;
	}

	public function RememberMe()
	{
		if (isset($_POST['rememberMe']))
			return true;
		return false;
	}

	public function GenerateHTML($loginStatus)
	{
		//Login form.
		if (!$loginStatus)
		{
			$nameInput = $this->GetUsernameInput();
			$HTMLString = 	"<h1>Laborationskod hk222gn</h1>
							<a href='?register'>Registrera ny användare</a>
							<form name='f1' method='post' action='?login'>
							<h3>Användarnamn</h3>
							<input type='text' name='username' value='$nameInput'>
							<h3>Lösenord</h3>
							<input type='password' name='password'>
							<input type='submit' value='Logga in' name='doLogin'>
							<h3>Kom ihåg mig!</h3>
							<input type ='checkbox' name='rememberMe' value='1'>
							</form>";
		}
		else
		{
			$username = $this->model->GetUsername();
			$HTMLString = 	"<form name='f2' method='post' action='?logout'>
							<input type='submit' value='Logga ut' name='doLogout'>
							</form>";
		}

		//Grab the feedback mesesage
		$feedbackMsg = $this->GetFeedbackMessage();

		//Add the feedback message if there is one.
		if (!$feedbackMsg == "") 
		{
			$HTMLString .= "<div id = 'feedback' >" . $feedbackMsg . "</div>";
		}
		return $HTMLString;
	}

	public function SetFeedbackMessage($msg)
	{
		$this->feedbackMessage = $msg;
	}

	public function GetFeedbackMessage()
	{
		return $this->feedbackMessage;
	}

    public function SaveUserCookie($name, $tempPW)
    {
    	$cookieExpirationTime = time() + 60 * 60;

    	if ($this->AreCookiesSet()) 
    	{
    		$this->UnsetUserCookies();
    	}
        setcookie(self::$usernameFieldAndCookieName, $name, $cookieExpirationTime);
        setcookie(self::$passwordFieldAndCookieName, $tempPW, $cookieExpirationTime);

        $this->model->StoreCookieExpirationTime($name, $cookieExpirationTime);

        return "Inloggningen lyckades och vi kommer ihåg dig nästa gång!";
    }

    public function UnsetUserCookies()
    {
    	unset($_COOKIE[self::$usernameFieldAndCookieName]);
		unset($_COOKIE[self::$passwordFieldAndCookieName]);
	 	setcookie(self::$usernameFieldAndCookieName, "", time() - 3600);
        setcookie(self::$passwordFieldAndCookieName, "", time() - 3600);
    }

    public function AreCookiesSet()
    {
    	if (isset($_COOKIE[self::$usernameFieldAndCookieName]) && isset($_COOKIE[self::$passwordFieldAndCookieName])) 
    	{
    		return true;
    	}
    	return false;
    }

    public function GetUsernameCookie()
    {
    	if (isset($_COOKIE[self::$usernameFieldAndCookieName])) 
    	{
    		return $_COOKIE[self::$usernameFieldAndCookieName];
    	}
    	return "";
    }

    public function GetPasswordCookie()
    {
    	if (isset($_COOKIE[self::$passwordFieldAndCookieName])) 
    	{
    		return $_COOKIE[self::$passwordFieldAndCookieName];
    	}
    	return "";
    }
}