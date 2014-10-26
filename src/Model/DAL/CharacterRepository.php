<?php

require_once("model/DAL/WeaponRepository.php");

class CharacterRepository extends Repository
{
	private $weaponRepository;

	private static $userID = "UserID"; //<- Primary key, taken from Users Entry
	private static $name = "Name";
	private static $maxHealth = "MaxHealth";
	private static $currentHealth = "CurrentHealth";
	private static $attack = "Attack";
	private static $defense = "Defense";
	private static $level = "Level";
	private static $exp = "Exp";
	private static $gold = "Gold";
	private static $statPoints = "StatPoints";
	private static $weapon = "WeaponEntry";
	
	public function __construct($weaponRepository)
	{
		$this->DBTable = "characters";
		$this->weaponRepository = $weaponRepository;
	}

	public function AddCharacter(Character $char, $userID)
	{
		try
		{
			$DB = $this->connection();

			$sql = "INSERT INTO $this->DBTable (" . self::$userID . ", " . self::$name . ", " . self::$maxHealth . ", " . self::$currentHealth . ", " . self::$attack . ", " . self::$defense 
				   . ", " . self::$level . ", " . self::$exp . ", " . self::$gold . ", " . self::$statPoints . ", " . self::$weapon . ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$params = array($userID, $char->GetName(), $char->GetMaxHealth(), $char->GetCurrentHealth(), $char->GetAttack(), $char->GetDefense(), $char->GetLevel(), $char->GetExp(), $char->GetGold(), $char->GetStatPoints(), $char->GetWeapon()->GetEntry());

			$query = $DB->prepare($sql);
			$query->execute($params);
		}
		catch (PDOException $e)
		{
			die("Error 2");
		}
	}

	public function GetCharacterByUserID($userID)
	{
		try 
		{
			$DB = $this->connection();

			$sql = "SELECT * FROM $this->DBTable WHERE " . self::$userID . " = ?";
			$param = array($userID);

			$query = $DB->prepare($sql);
			$query->execute($param);

			$result = $query->fetch();

			if ($result)
			{
				//Get the users weapon.
				$weapon = $this->weaponRepository->GetWeapon($result[self::$weapon]);

				$character = new Character($result[self::$name], $result[self::$maxHealth], $result[self::$attack], 
										   $result[self::$defense], $result[self::$level], $result[self::$exp], $result[self::$gold], $result[self::$statPoints], $weapon);

				return $character;
			}

			return NULL;
		}
		catch (PDOException $e)
		{
			die("Error 1");
		}
	}

	public function SaveCharacterToDB(Character $char, $userID)
	{
		try
		{
			$DB = $this->connection();

			$sql = "UPDATE $this->DBTable SET " . self::$maxHealth . " = ?, " . self::$currentHealth . " = ?, " . self::$attack . " = ?, " . self::$defense 
				   . " = ?, " . self::$level . " = ?, " . self::$exp . " = ?, " . self::$gold . " = ?, " . self::$statPoints . " = ?, " . self::$weapon . " = ?";

			$params = array($char->GetMaxHealth(), $char->GetCurrentHealth(), $char->GetAttack(), $char->GetDefense(), $char->GetLevel(), $char->GetExp(), $char->GetGold(), $char->GetStatPoints(), $char->GetWeapon()->GetEntry());

			$query = $DB->prepare($sql);
			$query->execute($params);
		}
		catch (PDOException $e)
		{
			die("Error 3 save");
		}
	}

	public function RemoveCharacterByUserID($userID)
	{
		try
		{
			$DB = $this->connection();

			$sql = "DELETE FROM $this->DBTable WHERE " . self::$userID . " = ?";

			$params = array($userID);

			$query = $DB->prepare($sql);
			$query->execute($params);
		}
		catch (PDOException $e)
		{
			die("Error 4");
		}
	}
}