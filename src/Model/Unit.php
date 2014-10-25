<?php

class Unit
{
	public $name = "";
	public $maxHealth;
	public $currentHealth;
	public $attack;
	public $defense;

	public $level;

	public function GetName()
	{
		return $this->name;
	}

	public function GetMaxHealth()
	{
		return $this->maxHealth;
	}

	public function GetCurrentHealth()
	{
		return $this->currentHealth;
	}

	public function TakeDamage($damage)
	{
		$this->currentHealth = $this->currentHealth - $damage;
	}

	public function GetAttack()
	{
		return $this->attack;
	}

	public function GetDefense()
	{
		return $this->defense;
	}

	public function GetLevel()
	{
		return $this->level;
	}

}