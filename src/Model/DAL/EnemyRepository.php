<?php

class EnemyRepository extends Repository
{
	private static $entry = "Entry"; //<- Primary key, taken from Users Entry
	private static $name = "Name";
	private static $maxHealth = "MaxHealth";
	private static $currentHealth = "CurrentHealth";
	private static $attack = "Attack";
	private static $defense = "Defense";
	private static $level = "Level";
	private static $expOnKill = "ExpOnKill";
	private static $goldOnKill = "GoldOnKill";

	public function __construct()
	{
		$this->DBTable = "enemy";
	}

	public function GetEnemyByEntry($entry)
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
				$enemy = new Enemy($result[self::$name], $result[self::$maxHealth], $result[self::$attack], 
										   $result[self::$defense], $result[self::$level], $result[self::$expOnKill], $result[self::$goldOnKill]);

				return $enemy;
			}

			return NULL;
		}
		catch (PDOException $e)
		{
			die("Error 1 enemy");
		}
	}

}