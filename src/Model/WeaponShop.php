<?php

require_once("model/DAL/WeaponRepository.php");

class WeaponShop
{
	private $weaponRepository;
	const HIGHEST_UPGRADE = 5;

	public function __construct($weaponRepository)
	{
		$this->weaponRepository = $weaponRepository;
	}
	
	public function GetWeapon($entry)
	{
		return $this->weaponRepository->GetWeapon($entry);
	}

	public function BuyWeapon($entry, $gold)
	{
		if ($entry > WeaponShop::HIGHEST_UPGRADE)
			return NULL;

		$weapon = $this->weaponRepository->GetWeapon($entry);

		if ($weapon->GetPrice() > $gold)
			return NULL;
		return $weapon;
	}
}