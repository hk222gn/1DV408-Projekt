<?php

class User
{
	//Key <- Is just a unique identifier in the DB, nothing else.
	private $name;
	private $password;
	private $tempPW;
	private $tempPWExpiration;

	public function __construct($name, $password, $tempPW = NULL, $tempPWExpiration = NULL)
	{
		$this->name = $name;
		$this->password = $password;
		$this->tempPW = $tempPW;
		$this->tempPWExpiration = $tempPWExpiration;
	}

	public function GetName()
	{
		return $this->name;
	}

	public function GetPassword()
	{
		return $this->password;
	}

	public function GettempPW()
	{
		return $this->tempPW;
	}

	public function GettempPWExpiration()
	{
		return $this->tempPWExpiration;
	}
}