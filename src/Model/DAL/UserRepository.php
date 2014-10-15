<?php

require_once("model/DAL/Repository.php");

class UserRepository extends Repository
{
	private static $entry = "Entry";//Key <- Is just a unique identifier in the DB
	private static $name = "Name";
	private static $password = "Password";
	private static $tempPW = "TempPW";
	private static $tempPWExpiration = "TempPWExpiration";

	public function __construct()
	{
		$this->DBTable = "user";
	}

	public function AddUser(User $user)
	{
		try
		{
			$DB = $this->connection();

			$sql = "INSERT INTO $this->DBTable (" . self::$name . "," . self::$password . ") VALUES (?, ?)";
			$params = array($user->GetName(), $user->GetPassword());

			$query = $DB->prepare($sql);
			$query->execute($params);
		}
		catch (PDOException $e)
		{
			die("Error 2");
		}
	}

	public function GetUserByName($name)
	{
		try 
		{
			$DB = $this->connection();

			$sql = "SELECT * FROM $this->DBTable WHERE " . self::$name . "= ?";
			$param = array($name);

			$query = $DB->prepare($sql);
			$query->execute($param);

			$result = $query->fetch();

			if ($result)
			{
				$user = new User($result[self::$name], $result[self::$password], $result[self::$tempPW], $result[self::$tempPWExpiration]);

				return $user;
			}

			return NULL;
		}
		catch (PDOException $e)
		{
			die("Error 1");
		}
	}

	public function GetUserEntryByName($name)
	{
		try 
		{
			$DB = $this->connection();

			$sql = "SELECT " . self::$entry . " FROM $this->DBTable WHERE " . self::$name . "= ?";
			$param = array($name);


			$query = $DB->prepare($sql);
			$query->execute($param);

			$result = $query->fetch();

			if ($result)
			{
				return $result[self::$entry];
			}

			return NULL;
		}
		catch (PDOException $e)
		{
			die("Error 5");
		}
	}

	public function SetTempPW($name, $tempPW)
	{
		try
		{
			$DB = $this->connection();

			$sql = "UPDATE $this->DBTable SET " . self::$tempPW . "= ? WHERE name = ?";
			$params = array($tempPW, $name);

			$query = $DB->prepare($sql);
			$query->execute($params);
		}
		catch (PDOException $e)
		{
			die("Error 3");
		}
	}

	public function SetTempPWExpiration($name, $expirationTime)
	{
		try
		{
			$DB = $this->connection();

			$sql = "UPDATE $this->DBTable SET " . self::$tempPWExpiration . "= ? WHERE name = ?";
			$params = array($expirationTime, $name);

			$query = $DB->prepare($sql);
			$query->execute($params);
		}
		catch (PDOException $e)
		{
			die("Error 4");
		}
	}
}