<?php
require_once("model/DAL/UserRepository.php");
require_once("model/DAL/CharacterRepository.php");
require_once("model/DAL/EnemyRepository.php");
require_once("model/WeaponShop.php");
require_once("model/Character.php");
require_once("model/Enemy.php");

class GameModel
{ 
	private $userRepository;
	private $characterRepository;
	private $enemyRepository;
	private $weaponShop;
	
	//Log
	private static $logSession = "textLog";
	private static $maxLogRows = 7;

	//Unit storage
	private static $characterStorage = "characterStorage";
	private static $monsterStorage = "monsterStorage";

	public function __construct()
	{
		$this->userRepository = new UserRepository();
		$this->characterRepository = new CharacterRepository();
		$this->enemyRepository = new EnemyRepository();
		$this->weaponShop = new WeaponShop();
	}

	public function IsUserHunting()
	{
		if (isset($_SESSION[self::$monsterStorage]))
			return true;

		return false;
	}

	public function GetCurrentMonster()
	{
		if (isset($_SESSION[self::$monsterStorage]))
			return $_SESSION[self::$monsterStorage];
		return NULL;
	}

	public function StartHunting($charLevel)
	{
		$monster = $this->GetMonster($charLevel);

		if ($monster == NULL || $this->IsUserHunting())
			return false;

		$_SESSION[self::$monsterStorage] = $monster;

		$string = "You look around and find a " . $monster->GetName() . " to fight!";
		$monsterInfoString = "The " . $_SESSION[self::$monsterStorage]->GetName() . " has " . $_SESSION[self::$monsterStorage]->GetMaxHealth() . "/" . $_SESSION[self::$monsterStorage]->GetCurrentHealth()
							 . " health, " . $_SESSION[self::$monsterStorage]->GetAttack() . " attack and " . $_SESSION[self::$monsterStorage]->GetDefense() . " defense.";

		$this->AddTextToLog($string);
		$this->AddTextToLog($monsterInfoString);
	}

	public function GetMonster($charLevel)
	{
		$highestMonsterEntry = 4;
		//Every 5 levels you're up against a new monster
		$monsterEntry = floor($charLevel / 5);

		if ($monsterEntry > $highestMonsterEntry)
			$monsterEntry = $highestMonsterEntry;

		$enemy = $this->enemyRepository->GetEnemyByEntry($monsterEntry);

		return $enemy;
	}

	public function CalculateCombatResults($attackType, Character $char)
	{
		//Get the monsters attack of choice
		$monster = $this->GetCurrentMonster();

		$monsterAttack = $monster->GetAttackTypeChoice();

		//The attacks are of the same type, calculate damage normally.
		if ($attackType == $monsterAttack)
		{
			//Calculate the damage normally IE: attack vs defense
			$this->HandleAttackTypeWinner(true, $char, $monster);

			$this->HandleAttackTypeWinner(false, $char, $monster);
		}
		else
		{
			//Find out who won the attack
			switch ($attackType)
			{
				case AttackTypes::QUICK:
				if ($monsterAttack == AttackTypes::HEAVY)
				{
					$this->HandleAttackTypeWinner(true, $char, $monster);
				}
				else
				{
					$this->HandleAttackTypeWinner(false, $char, $monster);
				}
				break;

				case AttackTypes::NORMAL:
				if ($monsterAttack == AttackTypes::QUICK)
				{
					$this->HandleAttackTypeWinner(true, $char, $monster);
				}
				else
				{
					$this->HandleAttackTypeWinner(false, $char, $monster);
				}
				break;

				case AttackTypes::HEAVY:
				if ($monsterAttack == AttackTypes::NORMAL)
				{
					$this->HandleAttackTypeWinner(true, $char, $monster);
				}
				else
				{
					$this->HandleAttackTypeWinner(false, $char, $monster);
				}
				break;
			}
		}

		if ($char->GetCurrentHealth() <= 0)
		{
			$this->AddTextToLog("You have died! Press end game to create a new character.");
			return false;
		}

		if ($monster->GetCurrentHealth() <= 0)
		{
			$expReward = $monster->GetExpOnKill();
			$goldReward = $monster->GetGoldOnKill();

			$this->AddTextToLog("You gain " . $goldReward . " Gold and " . $expReward . " Exp for slaying " . $monster->GetName() . "!");

			$char->AddExp($expReward);
			$char->AddGold($goldReward);

			unset($_SESSION[self::$monsterStorage]);
		}

		return true;
	}

	private function HandleAttackTypeWinner($didPlayerWin, Character $char, Enemy $monster)
	{
		if ($didPlayerWin)
		{
			$totalCharAttack = $char->GetWeapon()->GetAttack() + $char->GetAttack();
			$actualDamage = $this->CalculateDamage($totalCharAttack, $monster->GetDefense());
			$monster->TakeDamage($actualDamage);
			$this->LogAttackInformation(true, $actualDamage, $monster->GetName(), $monster->GetCurrentHealth(), $monster->GetMaxHealth());
		}
		else
		{
			$totalCharDefense = $char->GetWeapon()->GetDefense() + $char->GetDefense();
			$actualDamage = $this->CalculateDamage($monster->GetAttack(), $totalCharDefense);
			$char->TakeDamage($actualDamage);
			$this->LogAttackInformation(false, $actualDamage, $monster->GetName());
		}
	}

	public function HandleUserDeath($entry)
	{
		//Unset all game sessions.
		unset($_SESSION[self::$characterStorage]);
		unset($_SESSION[self::$monsterStorage]);
		unset($_SESSION[self::$logSession]);

		//Remove the character from the DB
		$this->characterRepository->RemoveCharacterByUserID($entry);
	}

	public function CheckForPlayerLevelup(Character $char, $entry)
	{
		if ($char->GetExp() >= $char->GetLevel() * Character::REQUIRED_EXP_MULTI)
		{
			$char->LevelUp();
			$this->characterRepository->SaveCharacterToDB($char, $entry);
		}
	}

	public function AddStat(Character $char, $entry, $addHealth, $addAttack, $addDefense)
	{
		if ($char->GetStatPoints() <= 0)
		{
			$this->AddTextToLog("You don't have any stat points!");
			return false;
		}

		//TODO: Do i want to save the stats to the DB instantly? Can do it with the update functions i wrote
		if ($addHealth)
		{
			$char->PutPointInHealth();
			$this->AddTextToLog("You added 1 stat point to health(+" . Character::HEALTH_PER_STAT . ")!");
		}
		else if ($addAttack)
		{
			$char->PutPointInAttack();
			$this->AddTextToLog("You added 1 stat point to attack(+1)!");
		}
		else
		{
			$char->PutPointInDefense();
			$this->AddTextToLog("You added 1 stat point to defense(+1)!");
		}
	}

	//TODO: I think we should move this to a "shop" class completely, to easier handle everything, we need to move out the text logging too if that's the case
	public function BuyItem(Character $char, $buyHealthPotion, $buyWeapon)
	{
		if ($buyHealthPotion)
		{
			if ($char->GetCurrentHealth() == $char->GetMaxHealth())
			{
				$this->AddTextToLog("You already have max health, do not waste your gold!");
				return false;
			}

			if ($char->GetGold() < HealthPotion::HEALTH_POTION_PRICE)
			{
				$this->AddTextToLog("You don't have enough gold for a Health Potion! You need " . HealthPotion::HEALTH_POTION_PRICE .  "g");
				return false;
			}
			$char->RemoveGold(HealthPotion::HEALTH_POTION_PRICE);
			$char->UseHealthPotion();
			$this->AddTextToLog("You purchased a health potion(+" . HealthPotion::HEAL_AMOUNT . " health) for " . HealthPotion::HEALTH_POTION_PRICE . "g");
		}

		if ($buyWeapon)
		{

			//																	   +1 to buy the next upgrade.
			$weapon = $this->weaponShop->BuyWeapon($char->GetWeapon()->GetEntry() + 1, $char->GetGold());

			if ($weapon != NULL)
			{
				$char->ChangeWeapon($weapon);
				$char->RemoveGold($weapon->GetPrice());
				$this->AddTextToLog("Weapon upgraded!");

			}
			else
				$this->AddTextToLog("You don't have enough gold or reached the maximum upgrade (" . WeaponShop::HIGHEST_UPGRADE . ")");

		}
	}

	public function AddTextToLog($input)
	{
		if (!isset($_SESSION[self::$logSession]) || count($_SESSION[self::$logSession]) == 0)
			$_SESSION[self::$logSession] = array();

		array_push($_SESSION[self::$logSession], $input);

		$count = count($_SESSION[self::$logSession]);

		if ($count > self::$maxLogRows)
		{
			$count = $count - self::$maxLogRows;

			for ($i = $count; $i > 0; $i--) { 
				unset($_SESSION[self::$logSession][$i - 1]);
			}

			$_SESSION[self::$logSession] = array_values($_SESSION[self::$logSession]);
		}
	}

	public function GetLogArray()
	{
		if (isset($_SESSION[self::$logSession]))
			return $_SESSION[self::$logSession];
		return "";
	}

	public function GetUserEntry($username)
	{
		return $entry = $this->userRepository->GetUserEntryByName($username);
	}

	public function GetCharacterFromSession()
	{
		if (isset($_SESSION[self::$characterStorage]))
			return $_SESSION[self::$characterStorage];
		return NULL;
	}

	public function SaveCharToSession(Character $char)
	{
		if (isset($_SESSION[self::$characterStorage]))
			return;
		$_SESSION[self::$characterStorage] = $char;
	}

	public function GetCharacterFromDB($entry)
	{
		$character = $this->characterRepository->GetCharacterByUserID($entry);

		if ($character != NULL)
			return $character;
		return NULL;
	}

	public function CheckForExistingCharacter($entry)
	{
		if ($this->characterRepository->GetCharacterByUserID($entry))
			return true;
		return false;
	}

	public function CreateCharacter($characterName, $entry)
	{
		if (preg_match('/[^A-Za-z0-9-_!åäöÅÄÖ]/', $characterName))
		{
			return "Karaktärens namn innehåller ogiltiga tecken!";
		}

		$char = new Character($characterName, 25, 3, 1, 1, 0, 0, 0, $this->weaponShop->GetWeapon(0));

		$this->characterRepository->AddCharacter($char, $entry);

		$this->AddTextToLog("Character created! Welcome to the game!");
	}

	private function LogAttackInformation($playerWon, $attackerDamage, $monsterName, $attackedCHP = 0, $attackedMaxHP = 0)
	{
		if ($playerWon)
			$this->AddTextToLog("You deal " . $attackerDamage . " damage to " . $monsterName . "." . " HP is " . $attackedCHP . "/" . $attackedMaxHP);
		else
			$this->AddTextToLog("You take " . $attackerDamage . " damage from " . $monsterName . "!");
	}

	private function CalculateDamage($AttackerDamage, $AttackedDefense)
	{
		$defenseEffectiveness = 0.5; //50% effectiveness

		$damage = $AttackerDamage - ($AttackedDefense * $defenseEffectiveness);

		if ($damage < 0)
				$damage = 0;

		return $damage;
	}
}