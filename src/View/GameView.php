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
		return AttackTypes::NONE;
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
	
	public function GenerateGameHTML($isUserHunting = false, $logArray, $name, $maxHealth, $currentHealth, $attack, $defense, $gold, $level, $exp, $statPoints, $weaponName, $weaponDamage, $weaponDefense, $nextWepPrice, $weaponUpgrade, $playerDied = false)
	{
		$log = $this->GenerateLogString($logArray);

		$weaponPriceHTML;
		if ($nextWepPrice != 0)
			$weaponPriceHTML = "<a href='?buy=weapon' class = 'btn btn-default btn-xs margintop'> Upgrade (" . $nextWepPrice . "g)!</a>";
		else
			$weaponPriceHTML ="Max upgrade!";

		$HTML = "
				<div class = 'row'>
					<div class = 'col-xs-6 col-sm-7'>
						<div class = 'log'>
							$log
						</div>
						<div class ='first'>
							<h4>Actions</h4>
							";

					if ($isUserHunting)
						$HTML .= $this->GetHuntingHTML();
					else if ($playerDied)
						$HTML .= "<p><a href = '?endGame' class = 'btn btn-success btn-xs margin'>End game</a></p>";
					else
						$HTML .= $this->GetActionHTML();
					$HTML .=
							"
						</div>
					</div>
				
					<div class = 'col-xs-6 col-sm-5 second'>
						<h4>Character information.</h4>
						<p>Name: $name</p>
						<p>Level: $level ($statPoints) stat pts</p>
						<p>Experience: $exp/" . $level * Character::REQUIRED_EXP_MULTI . "</p>
						<p><h4><a href = '?addStat=health' class = 'btn btn-default btn-xs margintop'> + </a><span class='label label-info marginleft'>Health: $currentHealth/$maxHealth</span></h4></p>
						<p><h4><a href = '?addStat=attack' class = 'btn btn-default btn-xs margintop'> + </a><span class='label label-info marginleft'>Attack: $attack</span></h4></p>
						<p><h4><a href = '?addStat=defense' class = 'btn btn-default btn-xs margintop'> + </a><span class='label label-info marginleft'>Defense: $defense</span></h4></p>
						<br />
						<h4>Shop.</h4>
						<p>Current gold: $gold</p>
						<p>Weapon: $weaponName (+$weaponDamage attack +$weaponDefense defense), $weaponPriceHTML</a></p>
						<p><a href='?buy=healthPotion' class = 'btn btn-default btn-xs margintop'>Buy potion </a>" . HealthPotion::HEALTH_POTION_PRICE . "g (+" . HealthPotion::HEAL_AMOUNT . " health instantly) </p>
					</div>
				</div>";


		return $HTML;
	}

	public function GetHuntingHTML()
	{
		$HTML = "
				<p><a href = '?attack=" . AttackTypes::QUICK  . "' class = 'btn btn-success btn-xs margin'>" . AttackTypes::QUICK  . " attack</a></p>
				<p><a href = '?attack=" . AttackTypes::NORMAL . "' class = 'btn btn-warning btn-xs margin'>" . AttackTypes::NORMAL . " attack</a></p>
				<p><a href = '?attack=" . AttackTypes::HEAVY  . "' class = 'btn btn-danger btn-xs margin'>" . AttackTypes::HEAVY  . " attack</a></p>
				";

		return $HTML;
	}

	public function GetActionHTML()
	{
		$HTML =	"
				<p><a href = '?hunt' class = 'btn btn-primary btn-sm margin'>Start Hunting!</a></p>
				";
		return $HTML;
	}

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