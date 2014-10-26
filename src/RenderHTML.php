<?php

class HTMLRenderer
{
	public function RenderHTML($body = "")
	{
		if ($body === NULL) 
		{
			$body = "NULL";
		}

		$day = utf8_encode(strftime("%A"));
		//
		echo 	"
				<!DOCTYPE html>
				<html lang=\"sv\">
				<head>
					<title>Lab2</title>
					<meta charset=\"utf-8\">
					<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
					<link rel ='stylesheet' href ='style/style.css'>
				</head>
				<body>
					<div class = 'container'>
						<div class = 'page-header'><h1 class ='center'>Welcome to..<h1>
						<h3 class ='center'>How far can you hunt!</h3></div>
						$body
						<div class = 'footer'>"
						. strftime("$day, den %d %B år %Y. Klockan är [%X].") //gmdate("[H:i:s].", time() + 2 * 60 * 60)
						. 
						"</div>
					</div>
					<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
    				<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
				</body>
				</html>";
	}
}