<?php

class Database
{
	//Database Parameters

   
    private $servername='172.17.0.2';
    private $username='root';
    private $password='test';
    private $database='gallery';
    private $conn ;

    public function getMYSQLI()
    {
    	$this->conn = null;
    	$this->conn=mysqli_connect($this->servername,$this->username,$this->password,$this->database);
    	return $this->conn;
    }
}
?>