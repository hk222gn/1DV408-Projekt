<?php

//TODO: Är det verkligen en bra lösning i ValidateUserInput, if username & password == ""?
require_once("model/DAL/UserRepository.php");
require_once("model/User.php");

class RegisterModel 
{
	private $didRegistrationSucceed = false;
	private $userRepository;

	public function __construct($userRepo)
	{
		$this->userRepository = $userRepo;
	}

	public function RegisterUser($username, $password, $passwordCheck)
	{
		if (strlen($username) < 3)
		{
			if (strlen($password) < 6) 
			{
				return "Användarnamnet har för få tecken, Minst 3 krävs. <br/> Lösenordet har för få tecken, Minst 6 krävs.";
			}
			return "Användarnamnet har för få tecken, Minst 3 krävs.";
		}

		if (strlen($password) < 6)
		{
			return "Lösenordet har för få tecken, Minst 6 krävs.";
		}
		else if ($password != $passwordCheck)
		{
			return "Lösenorden matcher inte.";
		}

		//Implement this!
		if ($this->CheckIfUsernameIsTaken($username))
		{
			return "Användarnamnet är upptaget.";
		}

		//If any other characters than the ones below, it will return true.
		if (preg_match('/[^A-Za-z0-9-_!åäöÅÄÖ]/', $username))
		{
			return "Användarnamnet innehåller ogiltiga tecken.";
		}

		//Add the user to the DB
		$this->userRepository->AddUser(new User($username, $password));

		$this->didRegistrationSucceed = true;

		return "Registrering av ny användare lyckades!";
	}

	public function CheckIfUsernameIsTaken($username)
	{
		$user = $this->userRepository->GetUserByName($username);

		if ($user == NULL)
			return false;

		return true;
	}

	public function DidRegistrationSucceed()
	{
		return $this->didRegistrationSucceed;
	}
}