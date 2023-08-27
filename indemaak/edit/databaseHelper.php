<?php

class dataBaseContext
{
	//Connect To Database
	const HOST = 'localhost';
	const USER='alexhxl195_Test';
	const PASSWORD ='test123';
	const DB ='alexhxl195_TestDb';
	const TESTTABEL= 'TestTabel';

	private $dbConnection;


	function dataBaseContext()
	{		
		$conn = new mysqli(self::HOST,self::USER,self::PASSWORD, self::DB);

		if ($conn->connect_error)
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$this->dbConnection = $conn;
	}

	public function setLogin($username, $password)
	{
		$hashed_password = password_hash($password, PASSWORD_BCRYPT);

		$sql = $this->dbConnection->prepare("INSERT INTO siteUser (naam, hashie) VALUES(?,?)");
		$sql->bind_param('ss', $username, $hashed_password);
		$sql->execute();
		$sql->close();	
	}

	public function checkLogin($username, $password)
	{
		//return true;

		$sql = $this->dbConnection->prepare("SELECT hashie FROM siteUser WHERE naam=?");
		$sql->bind_param('s', $username);
		$sql->execute();
		
		$result = $sql->get_result();

		if ($result)
		{
			$r = $result->fetch_assoc();
	     	$dbHash = $r["hashie"];
	     	$sql->close();

	     	if (password_verify($password, $dbHash))
	     	{	     		
	     		return true;
	     	}
	   	}

   		return false;
	}

	public function listEntries()
	{
		$sql = $this->dbConnection->prepare('SELECT * FROM ' . self::TESTTABEL);
		
		$sql->execute();

		$result = $sql->get_result();

		//$result = mysql_query($query);
		if($result) 
		{
			$entries = array();
		   	while($r = $result->fetch_assoc()) {
		     	$entry = new Entry();
		     	$entry->fillWithId($r["ID"],$r["Naam"], $r["Pad"], date("Y-m-d"));
		     	array_push($entries, $entry);	
		   	}

		   	return $entries;
		}
		else 
		{
			print "listEntries failed: " . mysqli_error();
		}

		$sql->close();
	}

	public function updateEntry($entry)
	{		
		$sql = $this->dbConnection->prepare("UPDATE " . self::TESTTABEL . " SET Naam=?, Pad=? WHERE ID=?");
		$sql->bind_param('ssi', $entry->Naam, $entry->Pad, $entry->getId());
		$sql->execute();
		$sql->close();        
	}

	public function insertEntry($entry)
	{
		echo $entry->Naam;

		$sql = $this->dbConnection->prepare("INSERT INTO " . self::TESTTABEL . " (Naam, Pad) VALUES(?,?)");
		$sql->bind_param('ss', $entry->naam, $entry->pad);
		$result = $sql->execute();
		
		if (!$result)
	    {
		  	echo("Insert entry failed: " . mysqli_error($this->dbConnection));
	    }

		$sql->close();
	}

	public function deleteEntry($entry)
	{
		$sql = $this->dbConnection->prepare("DELETE FROM " . self::TESTTABEL . " WHERE ID=?");
		$sql->bind_param('i', $entry->id);
		$result = $sql->execute();

		if (!$result)
	    {
		  	echo("Delete entry failed: " . mysqli_error($this->dbConnection));
	    }

		$sql->close();
	}

	public function Close()
	{
		mysql_close($db_handle);
	}
}

?>