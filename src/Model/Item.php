<?php

class Item
{
	public $name;
	public $entry;
	//Item stats.
	public $attack;
	public $defense;
	public $health;

	public $price;

	public function GetEntry()
	{
		return $this->entry;
	}

	public function GetName()
	{
		return $this->name;
	}

	public function GetAttack()
	{
		return $this->attack;
	}

	public function GetDefense()
	{
		return $this->defense;
	}

	public function GetHealth()
	{
		return $this->health;
	}

	public function GetPrice()
	{
		return $this->price;
	}
}