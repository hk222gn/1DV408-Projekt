<?php

class GameView
{
	private static $logSession = "textLog";
	private static $maxLogRows = 7;

	private $feedbackMessage = "";

	public function DidUserStartHunting()
	{
		if (isset($_POST['hunt'])) // TODO; RÄTT?
			return true;
		return false;
	}

	public function AddTextToLog($input)
	{
		if (!isset($_SESSION[self::$logSession]) || count($_SESSION[self::$logSession]) == 0)
			$_SESSION[self::$logSession] = Array();

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

	public function RenderLog()
	{
		$log = $this->GetLogArray();

		if ($log == "")
			return $log;

		$HTML = "";

		foreach ($log as $row)
		{
			$HTML .= "<p>$row</p>\n"; //TODO: Check how it turns out with a \n.
		}

		return $HTML;
	}
	
	public function GenerateGameHTML()
	{
		$log = $this->RenderLog();

		$HTML = "
				<div id = 'feedbackBox'>
					$log
				</div>
				<div id = 'playerStatsAndShop'>
					<h4>Character information.</h4>
					<p>Name: </p>
					<p>Health:</p>
					<p>Attack:</p>
					<p>Defense:</p>
					<br />
					<h4>Shop.</h4>
					<p>Weapon: Copper Sword, <a href='#'>Upgrade for 50g!</a></p>
					<p>Armor: &nbsp;&nbsp;&nbsp;Platinum armor, &nbspMax upgrade!</p>
					<p><a href='#'>Buy potion 5g!</a></p>
				</div>
				<div id = 'actionBox'>
					<h4>Actions</h4>
					<p><a href = '?hunt'>Hunt in the area</a></p>
					<p><a href = '?right'>Move right</a></p>
					<p><a href = '?up'>Move up</a></p>
					<p><a href = '?down'>Move down</a></p>
					<p><a href = '?left'>Move left</a></p>

				</div>
				";

		return $HTML;
	}

	// Borde kanske brytas ut till en egen vy..
	public function GenerateCharacterCreationHTML()
	{
		$characterInput = $this->GetCharacterNameInput();
		$HTML = "<div id = 'createCharacter'>
					<form name='f3' method='post' action='?createCharacter'>
						<h3>Choose a character name!</h3>
						<input type='text' name='characterName' value='$characterInput'>
						<input type='submit' value='Create' name='doCreate'>
					</form>
				</div>";

		$HTML .= $this->GetFeedbackMessage();

		return $HTML;
	}

	public function SetFeedbackMessage($msg)
	{
		$this->feedbackMessage = $msg;
	}

	public function GetFeedbackMessage()
	{
		return $this->feedbackMessage;
	}

	//
	public function GetCharacterNameInput()
	{
		if (isset($_POST['characterName']))
			return $_POST['characterName'];
		return false;
	}

	public function DidUserSendCreateCharacter()
	{
		if (isset($_POST['doCreate']))
			return true;
		return false;
	}
}
/*<p>Hunting: Wolf attacked you with 3 damage.</p>
					<p>Hunting: You attacked the wolf with 25 damage.</p>
					<p>Hunting: You've won the battle!</p>
					<p>Hunting: You find 10 gold coins.</p>
					<p>Console: You attempt to move right, but a wall was in the way.</p>
					<p>You bash your head in the wall and die.</p>
					<p>Max amount of lines to display is 7</p>
					*/