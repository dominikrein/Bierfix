<?php
	include_once('database.php');
	openConn();
	$query = executeQuery("SELECT * FROM `artikel_typen`;");

	$artikel = [];
	while($row = $query->fetch_array(MYSQLI_ASSOC)){
		$artikel[$row['id']] = $row;
	}
	echo json_encode($artikel);
?>