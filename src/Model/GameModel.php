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

		$char = new Character($characterName);//TODO: Make it so when a character is made, it's also saved to the DB, to avoid mistakes with different standard values.

		$this->characterRepository->AddCharacter($char, $entry);
	}
}