<?php
require_once("model/Unit.php");

class Character extends Unit
{
	private $gold = 0;
	private $currentExp = 0;

	public function __construct($name)
	{
		$this->name = $name;
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