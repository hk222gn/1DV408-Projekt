<?php
require_once("model/DAL/UserRepository.php");
require_once("model/DAL/CharacterRepository.php");
require_once("model/Character.php");

class GameModel
{ 
	private $userRepository;
	private $characterRepository;

	public function __construct()
	{
		$this->userRepository = new UserRepository();
		$this->characterRepository = new CharacterRepository();
	}

	public function GetUserEntry($username)
	{
		return $entry = $this->userRepository->GetUserEntryByName($username);
	}

	public function GetCharacter($entry)
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

		$char = new Character($characterName);

		$this->characterRepository->AddCharacter($char, $entry);
	}
}