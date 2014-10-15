<?php

class HTMLRenderer
{
	public function RenderHTML($body = "", $head = "")
	{
		if ($body === NULL) 
		{
			$body = "NULL";
		}

		$day = utf8_encode(strftime("%A"));

		echo 	"
				<!DOCTYPE html>
				<html lang=\"sv\">
				<head>
					<title>Lab2</title>
					<meta charset=\"utf-8\">
					<link rel = \"stylesheet\" href = \"style.css\" >
					$head
				</head>
				<body>
					<div id = 'container'>
						$body
						<div id = 'footer'>"
						. strftime("$day, den %d %B år %Y. Klockan är [%X].") //gmdate("[H:i:s].", time() + 2 * 60 * 60)
						. 
						"</div>
					</div>
				</body>
				</html>";
	}
}