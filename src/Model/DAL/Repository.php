<?php

class Repository
{
	private $DBUser = "root";
	private $DBPassword = "";
	private $DBConnectionString = "mysql:host=127.0.0.1;dbname=1dv408";
	private $DBConnection;
	private $DBTable;

	protected function connection()
	{
		if ($this->DBConnection == NULL)
			$this->DBConnection = new PDO($this->DBConnectionString, $this->DBUser, $this->DBPassword);

		$this->DBConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $this->DBConnection;
	}	
}