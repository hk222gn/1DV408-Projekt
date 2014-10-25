<?php

class GameView
{
	private $feedbackMessage = "";

	private static $addStat = "addStat";
	private static $buyItem = "buy";

	public function DidUserRequestBuy()
	{
		if (isset($_GET[self::$buyItem]))
			return true;
		return false;
	}

	public function DidUserBuyHealthPotion()
	{
		if ($_GET[self::$buyItem] == "healthPotion")
			return true;
		return false;
	}

	public function DidUserBuyWeapon()
	{
		if ($_GET[self::$buyItem] == "weapon")
			return true;
		return false;
	}

	public function DidUserRequestAddStat()
	{
		if (isset($_GET[self::$addStat]))
			return true;
		return false;
	}

	public function DidUserAddHealth()
	{
		if ($_GET[self::$addStat] == "health")
			return true;
		return false;
	}

	public function DidUserAddAttack()
	{
		if ($_GET[self::$addStat] == "attack")
			return true;
		return false;
	}

	public function DidUserAddDefense()
	{
		if ($_GET[self::$addStat] == "defense")
			return true;
		return false;
	}

	public function DidUserStartHunting()
	{
		if (isset($_GET['hunt']))
			return true;
		return false;
	}

	public function GetAttackType()
	{
		if (isset($_GET['attack']))
			return $_GET['attack'];
		return "EMPTY";
	}

	public function GenerateLogString($logArray)
	{
		if ($logArray == "")
			return $logArray;

		$logString = "";

		foreach ($logArray as $row)
		{
			$logString .= "<p>$row</p>\n";
		}

		return $logString;
	}
	
	public function GenerateGameHTML($isUserHunting = false, $logArray, $name, $maxHealth, $currentHealth, $attack, $defense, $gold, $level, $exp, $statPoints, $weaponName, $weaponDamage, $playerDied = false)
	{
		$log = $this->GenerateLogString($logArray);

		$HTML = "
				<div id = 'feedbackBox'>
					$log
				</div>
				<div id = 'playerStatsAndShop'>
					<h4>Character information.</h4>
					<p>Name: $name</p>
					<p>Level: $level ($statPoints) stat pts</p>
					<p>Experience: $exp/" . $level * Character::REQUIRED_EXP_MULTI . "</p>
					<p><a href = '?addStat=health'> + </a>Health: $currentHealth/$maxHealth</p>
					<p><a href = '?addStat=attack'> + </a>Attack: $attack</p>
					<p><a href = '?addStat=defense'> + </a>Defense: $defense</p>
					<br />
					<h4>Shop.</h4>
					<p>Current gold: $gold</p>
					<p>Weapon: $weaponName (+$weaponDamage attack), <a href='?buy=weapon'> Upgrade!</a></p>
					<p><a href='?buy=healthPotion'>Buy potion</a> 5g (+ " . HealthPotion::HEAL_AMOUNT . " health instantly) </p>
				</div>
				<div id = 'actionBox'>
					<h4>Actions</h4>
				";

		if ($isUserHunting)
			$HTML .= $this->GetHuntingHTML();
		else if ($playerDied)
			$HTML .= "<p><a href = '?endGame'>End game</a></p>";
		else
			$HTML .= $this->GetActionHTML();
		$HTML .=
				"
				</div>
				";

		return $HTML;
	}

	public function GetHuntingHTML()
	{
		$HTML = "
				<p><a href = '?attack=" . AttackTypes::QUICK  . "'>" . AttackTypes::QUICK  . " attack</a></p>
				<p><a href = '?attack=" . AttackTypes::NORMAL . "'>" . AttackTypes::NORMAL . " attack</a></p>
				<p><a href = '?attack=" . AttackTypes::HEAVY  . "'>" . AttackTypes::HEAVY  . " attack</a></p>
				";

		return $HTML;
	}

	//If get works the way i made it look above (attack=quick means Get returns quick) change that here aswell.
	public function GetActionHTML()
	{
		$HTML =	"
				<p><a href = '?hunt'>Hunt in the area</a></p>
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