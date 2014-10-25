<?php

require_once("model/Item.php");

class Weapon extends Item
{
	private $lifeOnHit;

	public function __construct($entry, $name, $attack, $defense, $health, $lifeOnHit, $price)
	{
		$this->entry = $entry;
		$this->name = $name;
		$this->attack = $attack;
		$this->defense = $defense;
		$this->health = $health;
		$this->lifeOnHit = $lifeOnHit;
		$this->price = $price;
	}

	public function GetLifeOnHit()
	{
		return $this->$lifeOnHit;
	}
}