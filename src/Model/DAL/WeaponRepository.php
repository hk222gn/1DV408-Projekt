<?php

require_once("model/weapon.php");

class WeaponRepository extends Repository
{
	private static $entry = "Entry";
	private static $name = "Name";
	private static $attack = "Attack";
	private static $defense = "Defense";
	private static $health = "Health";
	private static $lifeOnHit = "LifeOnHit";
	private static $price = "Price";

	public function __construct()
	{
		$this->DBTable = "weapons";
	}

	public function GetWeapon($entry)
	{
		try 
		{
			$DB = $this->connection();

			$sql = "SELECT * FROM $this->DBTable WHERE " . self::$entry . " = ?";
			$param = array($entry);

			$query = $DB->prepare($sql);
			$query->execute($param);

			$result = $query->fetch();

			if ($result)
			{
				$weapon = new Weapon($result[self::$entry], $result[self::$name], $result[self::$attack], 
								     $result[self::$defense], $result[self::$health], $result[self::$lifeOnHit], $result[self::$price]);

				return $weapon;
			}
			return NULL;
		}
		catch (PDOException $e)
		{
			die("Error 1 weapon");
		}
	}
}