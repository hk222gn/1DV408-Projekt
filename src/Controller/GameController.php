<?php

require_once("model/GameModel.php");
//require_once("model/LoginModel.php");
require_once("view/GameView.php");

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
		$character = $this->gameModel->CheckForExistingCharacter($this->gameModel->GetUserEntry($username));

		//No character exists? Create one.
		if ($character == false)
		{
			//If the user tried to create a character, make one, else show the creation form again.
			if ($this->gameView->DidUserSendCreateCharacter())
			{
				$feedback = $this->gameModel->CreateCharacter($this->gameView->GetCharacterNameInput(), $this->gameModel->GetUserEntry($username));
				if ($feedback != "")
				{
					$this->gameView->SetFeedbackMessage($feedback);
					return $this->gameView->GenerateCharacterCreationHTML();
				}
				else
					$this->gameView->AddTextToLog("Character created! Welcome to the game!"); //TODO: For some reason this sticks even if we log out, i guess it has something to do with the session log.
			}
			else
				return $this->gameView->GenerateCharacterCreationHTML();
		}

		$char = $this->gameModel->GetCharacter($this->gameModel->GetUserEntry($username));
		
		return $this->gameView->GenerateGameHTML($char->GetName(), $char->GetMaxHealth(), $char->GetCurrentHealth(), $char->GetAttack(), $char->GetDefense(), $char->GetGold());
	}
}