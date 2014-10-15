<?php

class Unit
{
	private $name = "";
	private $health = 25;
	private $attack = 0;
	private $defense = 0;

	private $level = 1;

	public function GetName()
	{
		return $this->name;
	}

	public function GetHealth()
	{
		return $this->health;
	}

	public function TakeDamage($damage)
	{
		$this->health = $this->health - $damage;
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