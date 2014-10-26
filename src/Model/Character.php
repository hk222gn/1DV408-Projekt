<?php
require_once("model/Unit.php");

class Character extends Unit
{
	private $gold;
	private $currentExp;
	private $statPoints;
	private $weapon;

	const HEALTH_PER_STAT    = 5;
	const REQUIRED_EXP_MULTI = 50;
	const STATS_PER_LEVEL    = 2;

	public function __construct($name, $maxHealth = 25, $attack = 5, $defense = 5, $level = 1, $exp = 0, $gold = 0, $statPoints = 0, $weapon = NULL)
	{
		$this->name = $name;
		$this->maxHealth = $maxHealth;
		$this->currentHealth = $maxHealth;
		$this->attack = $attack;
		$this->defense = $defense;
		$this->level = $level;
		$this->currentExp = $exp;
		$this->gold = $gold;
		$this->statPoints = $statPoints;
		$this->weapon = $weapon;
	}

	public function GetWeapon()
	{
		return $this->weapon;
	}

	public function ChangeWeapon(Weapon $weapon)
	{
		$this->weapon = $weapon;
	}

	public function GetGold()
	{
		return $this->gold;
	}

	public function GetExp()
	{
		return $this->currentExp;
	}

	public function AddExp($value)
	{
		$this->currentExp += $value;
	}

	public function LevelUp()
	{
		$this->level += 1;
		$this->currentExp = 0;
		$this->currentHealth = $this->maxHealth;
		$this->statPoints += Character::STATS_PER_LEVEL;
	}

	public function GetStatPoints()
	{
		return $this->statPoints;
	}

	public function PutPointInHealth()
	{
		$this->maxHealth += Character::HEALTH_PER_STAT;
		$this->currentHealth += Character::HEALTH_PER_STAT;
		$this->statPoints -= 1;
	}

	public function PutPointInAttack()
	{
		$this->attack += 1;
		$this->statPoints -= 1;
	}

	public function PutPointInDefense()
	{
		$this->defense += 1;
		$this->statPoints -= 1;
	}

	public function AddGold($value)
	{
		$this->gold += $value;
	}

	public function RemoveGold($value)
	{
		$this->gold -= $value;
	}

	public function UseHealthPotion()
	{
		$this->currentHealth += HealthPotion::HEAL_AMOUNT;

		if ($this->currentHealth > $this->maxHealth)
			$this->currentHealth = $this->maxHealth;

	}
}