<?php

require_once("model/GameModel.php");
require_once("view/GameView.php");
require_once("model/AttackTypes.php");
require_once("model/HealthPotion.php");

class GameController
{
	private $gameModel;
	private $gameView;

	public function __construct()
	{
		$this->gameModel = new GameModel();
		$this->gameView = new GameView();
	}

	public function HandleGame($username)
	{
		$userEntry = $this->gameModel->GetUserEntry($username);
		$character = $this->gameModel->CheckForExistingCharacter($userEntry);

		//No character exists? Create one.
		if ($character == false)
		{
			//If the user tried to create a character, make one, else show the creation form again.
			if ($this->gameView->DidUserSendCreateCharacter())
			{
				$feedback = $this->gameModel->CreateCharacter($this->gameView->GetCharacterNameInput(), $userEntry);

				//If there is feedback, it means something went wrong when creating the character.
				if ($feedback != "")
				{
					$this->gameView->SetFeedbackMessage($feedback);
					return $this->gameView->GenerateCharacterCreationHTML();
				}
			}
			else
				return $this->gameView->GenerateCharacterCreationHTML();
		}

		//Character did exist, check if it's allready saved in the session, else save it.
		if ($this->gameModel->GetCharacterFromSession() == NULL)
			$this->gameModel->SaveCharToSession($this->gameModel->GetCharacterFromDB($userEntry));

		$char = $this->gameModel->GetCharacterFromSession();

		//Check if user wants to add stats.
		if ($this->gameView->DidUserRequestAddStat())
			$this->gameModel->AddStat($char, $userEntry, $this->gameView->DidUserAddHealth(), $this->gameView->DidUserAddAttack(), $this->gameView->DidUserAddDefense());

		//Handle shop
		if ($this->gameView->DidUserRequestBuy())
			$this->gameModel->BuyItem($char, $userEntry, $this->gameView->DidUserBuyHealthPotion(), $this->gameView->DidUserBuyWeapon());

		$nextWeaponprice = $this->gameModel->GetNextWeaponPrice($char->GetWeapon()->GetEntry());

		//Handle the entire hunting part of the game, includes levels, player death.
		if ($this->HandleHunting($char, $userEntry))
		{
			$this->gameModel->CheckForPlayerLevelup($char, $userEntry);
		}
		else//This is run if the player dies.
		{
			return $this->gameView->GenerateGameHTML($this->gameModel->IsUserHunting(), $this->gameModel->GetLogArray(), $char->GetName(), $char->GetMaxHealth(), $char->GetCurrentHealth(), $char->GetAttack(), 
				   $char->GetDefense(), $char->GetGold(), $char->GetLevel(), $char->GetExp(), $char->GetStatPoints(), $char->GetWeapon()->GetName(), $char->GetWeapon()->GetAttack(), $char->GetWeapon()->GetDefense(), 
				   $nextWeaponprice, $char->GetWeapon()->GetEntry(), true);
		}
		
		return $this->gameView->GenerateGameHTML($this->gameModel->IsUserHunting(), $this->gameModel->GetLogArray(), $char->GetName(), $char->GetMaxHealth(), $char->GetCurrentHealth(), $char->GetAttack(), 
			   $char->GetDefense(), $char->GetGold(), $char->GetLevel(), $char->GetExp(), $char->GetStatPoints(), $char->GetWeapon()->GetName(), $char->GetWeapon()->GetAttack(), $char->GetWeapon()->GetDefense(), 
			   $nextWeaponprice, $char->GetWeapon()->GetEntry());
	}

	private function HandleHunting(Character $char, $entry)
	{

		if ($this->gameModel->IsUserHunting() && $this->gameView->GetAttackType() != AttackTypes::NONE)
		{
			$userDied = $this->gameModel->CalculateCombatResults($this->gameView->GetAttackType(), $char);

			if (!$userDied)
				$this->gameModel->HandleUserDeath($entry);

			return $userDied;
		}

		//Check if the user started the hunt
		if ($this->gameView->DidUserStartHunting())
		{
			//Create a monster for the user to fight.
			$this->gameModel->StartHunting($char->GetLevel());
		}
		return true;
	}
}