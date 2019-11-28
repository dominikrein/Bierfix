<?php
	$dbhost = "localhost";
	$dbuser = "bierfix";
	$dbpass = "bierfix";
	$db = "bierfix";
	$conn = null;

	function openConn(){
		global $dbhost, $dbuser, $dbpass,$db, $conn;
		$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connection to existing db failed: %s\n". $conn -> error);
	}

	function closeConn($conn){
		global $conn;
		$conn->close();
	}

	function getInsertID(){
		global $conn;
		return $conn->insert_id;
	}

	function executeQuery($sql){
		global $conn;
		if(!$conn){
			openConn();
		}
		$ret = $conn->query($sql);
		if(!$ret === TRUE){
			die("Query error: " . $conn->connect_error);
		}
		return $ret;
	}

	function beginTransaction(){
		global $conn;
		if(!$conn){
			openConn();
		}
		$conn->beginTransaction();
	}

	function commit(){
		global $conn;
		if(!$conn){
			openConn();
		}
		$conn->commit();
	}

	function executeHostMultiQuery($sql){
		//Querys die nicht direkt mit der DB connecten, bspw DB erstellen bzw droppen
		global $dbhost, $dbuser, $dbpass;
		$conn = new mysqli($dbhost, $dbuser, $dbpass);
		if($conn->connect_error){
			die("DB Conn error: " . $conn->connect_error);
		}		
		if(!$conn->multi_query($sql) === TRUE){
			die("Query error: " . $conn->error);
		}
		$conn->close();
	}

	function createDB(){	
		executeHostMultiQuery(file_get_contents('../sql/createBierfixDB.sql'));		
	}

	function dropDB(){
		executeHostMultiQuery(file_get_contents('../sql/dropBierfixDB.sql'));		
	}


?>