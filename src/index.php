<?php

require_once("RenderHTML.php");
require_once("controller/MainController.php");

session_start();
date_default_timezone_set("Europe/Stockholm");
setlocale(LC_ALL, "sv_SE");

$controller = new MainController();
$body = $controller->Run();

$view = new HTMLRenderer();
$view->RenderHTML($body);