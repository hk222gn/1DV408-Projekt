<?php
require_once("model/Unit.php");

class Character extends Unit
{
	private $gold;
	private $currentExp;

	public function __construct($name, $maxHealth = 25, $currentHealth = 25, $attack = 0, $defense = 0, $level = 1, $exp = 0, $gold = 0)
	{
		$this->name = $name;
		$this->maxHealth = $maxHealth;
		$this->currentHealth = $currentHealth;
		$this->attack = $attack;
		$this->defense = $defense;
		$this->level = $level;
		$this->currentExp = $exp;
		$this->gold = $gold;
	}

	public function GetGold()
	{
		return $this->gold;
	}

	public function GetExp()
	{
		return $this->currentExp;
	}
}