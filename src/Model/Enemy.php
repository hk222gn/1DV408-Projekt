<?php

class Enemy extends Unit
{
	private $expOnKill;
	private $goldOnKill;

	public function __construct($name, $maxHealth = 10, $attack = 1, $defense = 1, $level = 1, $expOnKill = 5, $goldOnKill = 5)
	{
		$this->name = $name;
		$this->maxHealth = $maxHealth;
		$this->currentHealth = $maxHealth;
		$this->attack = $attack;
		$this->defense = $defense;
		$this->level = $level;
		$this->expOnKill = $expOnKill;
		$this->goldOnKill = $goldOnKill;
	}

	public function GetGoldOnKill()
	{
		return $this->goldOnKill;
	}

	public function GetExpOnKill()
	{
		return $this->expOnKill;
	}

	public function GetAttackTypeChoice()
	{
		$rand = Rand(0, 2);

		switch ($rand)
		{
			case 0:
			$attackType = AttackTypes::QUICK;
			break;
			case 1:
			$attackType = AttackTypes::NORMAL;
			break;
			case 2:
			$attackType = AttackTypes::HEAVY;
			break;
		}

		return $attackType;
	}
}