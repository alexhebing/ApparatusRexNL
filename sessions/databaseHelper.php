<?php

class dataBaseContext
{
	//Connect To Database
	const HOST = 'localhost';
	const USER='alexhxl195_Test';
	const PASSWORD ='test123';
	const DB ='alexhxl195_TestDb';
	const VOLCASESSIONTABEL = 'Volcasession';
	const BOOMFACTORTABEL = 'Boomfactor';

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

	public function listSessions()
	{
		try {
   		echo 'hoi';

		// $sql = $this->dbConnection->prepare(
		// 	'SELECT vs.Id as Id,vs.Naam as Naam,PlaatjePad,AudioPad,DatumTitel,Beschrijving,bf.Naam as Boomfactor,Tempo,DatumToegevoegd FROM ' . self::VOLCASESSIONTABEL . ' as vs JOIN ' . self::BOOMFACTORTABEL . ' as bf ON BoomfactorId = bf.Id');
		
		// $sql->execute();

		// $result = $sql->get_result();

		// if($result) 
		// {
		// 	$sessions = array();
		//    	while($r = $result->fetch_assoc()) {
		//      	$session = new Volcasession();
		//      	$session->fillWithId($r["Id"],$r["DatumTitel"], $r["AudioPad"], $r["DatumToegevoegd"]);

		//      	$session->naam = $r["Naam"];
		//      	$session->plaatjePad = $r["PlaatjePad"];
		//      	$session->beschrijving = $r["Beschrijving"];
		//      	$session->boomFactor = $r["Boomfactor"];
		//      	$session->tempo = $r["Tempo"];

		//      	array_push($sessions, $session);	
		//    	}

		//    	return $sessions;
		// }
		// else 
		// {
		// 	print "listSessions failed: " . mysqli_error();
		// }

		// $sql->close();
		}
		//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
	}

	public function updateSession($session)
	{		
		$boomfactorId = $this->resolveBoomfactor($session->boomFactor);

		$sql = $this->dbConnection->prepare("UPDATE " . self::VOLCASESSIONTABEL . " SET DatumTitel=?, AudioPad=?, Naam=?, PlaatjePad=?, Beschrijving=?, BoomfactorId=?, Tempo=? WHERE ID=?");
		$sql->bind_param('sssssiii', $session->datumTitel, $session->audioPad, $session->naam, $session->plaatjePad, $session->beschrijving, $boomfactorId, $session->tempo, $session->id);
		$sql->execute();
		$sql->close();        
	}

	public function insertSession($session)
	{
		$boomfactorId = $this->resolveBoomfactor($session->boomFactor);		

		$sql = $this->dbConnection->prepare("INSERT INTO " . self::VOLCASESSIONTABEL . " (DatumTitel, AudioPad, Naam, PlaatjePad, Beschrijving, BoomfactorId, Tempo, DatumToegevoegd) VALUES(?,?,?,?,?,?,?,?)");
		$sql->bind_param('sssssiis', $session->datumTitel, $session->audioPad, $session->naam, $session->plaatjePad, $session->beschrijving, $boomfactorId, $session->tempo, $session->datumToegevoegd);
		$result = $sql->execute();
		
		if (!$result)
	    {
		  	echo("Insert session failed: " . mysqli_error($this->dbConnection));
	    }

		$sql->close();
	}

	public function deleteSession($session)
	{
		$sql = $this->dbConnection->prepare("DELETE FROM " . self::VOLCASESSIONTABEL . " WHERE Id=?");
		$sql->bind_param('i', $session->id);
		$result = $sql->execute();

		if (!$result)
	    {
		  	echo("Delete session failed: " . mysqli_error($this->dbConnection));
	    }

		$sql->close();
	}

	public function insertBoomfactor($naam)
	{
		$sql = $this->dbConnection->prepare("INSERT INTO " . self::BOOMFACTORTABEL . " (Naam) VALUES(?)");
		$sql->bind_param('s', $naam);
		$result = $sql->execute();
		
		if (!$result)
	    {
		  	echo("Insert boomfactor failed: " . mysqli_error($this->dbConnection));
	    }

		$sql->close();
	}

	public function listBoomfactors()
	{
		$sql = $this->dbConnection->prepare(
			'SELECT * FROM ' . self::BOOMFACTORTABEL);
		
		$sql->execute();

		$result = $sql->get_result();

		//$result = mysql_query($query);
		if($result) 
		{
			$boomfactors = array();
		   	while($r = $result->fetch_assoc()) {
		     	$boomfactor = new Boomfactor();
		     	$boomfactor->id = $r["Id"];
				$boomfactor->naam = $r["Naam"];
		     	array_push($boomfactors, $boomfactor);	
		   	}

		   	return $boomfactors;
		}
		else 
		{
			print "listBoomfactors failed: " . mysqli_error();
		}

		$sql->close();
	}

	private function resolveBoomfactor($naam)
	{
		$boomfactorId = 0;

		$boomfactors = $this->listBoomfactors();

		foreach ($boomfactors as $boomfactor) {
			if (strcasecmp($boomfactor->naam, $naam) == 0 ) {
				$boomfactorId = $boomfactor->id;
			}
		}

		if ($boomfactorId == 0) {
			$this->insertBoomfactor($naam);

			$boomfactors = $this->listBoomfactors();

			foreach ($boomfactors as $boomfactor) {
				if (strcasecmp($boomfactor->naam, $naam) == 0 ) {
					$boomfactorId = $boomfactor->id;
				}
			}
		}

		return $boomfactorId;
	}

	public function Close()
	{
		mysql_close($db_handle);
	}
}

?>