<?php

class DatabaseConnection
{
	private static ?DatabaseConnection $instance=null;
	private $connection;
	
	public function __construct()
	{
		$this->connection = new mysqli("localhost","root","","drugproject");
		if($this->connection->connect_error){
			echo "Something went wrong" . $this->connection->connect_error;
		}
	}
	public static function getInstance(){
		if(self::$instance==null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	public function getConnection(){
		return $this->connection;
	}
}
?>