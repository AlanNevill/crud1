<?php 
session_start();

define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASSWORD','snowball');
define('DB_NAME','db_crud');

class DB_con
{
	protected $conn;

	public function __construct()
	{
		try {
			$dns = "mysql:host=localhost;dbname=".DB_NAME;
			$this->conn = new PDO($dns, DB_USER, DB_PASSWORD,
				array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

		} catch (PDOException $e) {
			print_r("Error connecting to server: " . $e->getMessage() . "<br/>");
			die();

		}
		
	}

}

?>
