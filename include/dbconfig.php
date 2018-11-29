<?php 
session_start();

define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASSWORD','snowball');
define('DB_NAME','db_crud');

class DB_con
{
	protected $conn;
	function __construct()
	{
		$this->conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME) 
			or die('error connecting to server: '.mysqli_connect_error());
		
	}
}

?>
