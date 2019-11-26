<?php
    //EINSTELLUNGEN
    $festTitel = "2. Öschinger Weideabtrieb";
    $printerIp = "192.168.2.127";
    $statistik_dateiname = "../bierfix_statistik.csv";

	include_once('database.php');

    function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
        $reference_array = array();
    
        foreach($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }
    
        array_multisort($reference_array, $direction, $array);
    }

    $bestellungXml = new SimpleXMLElement($_POST["xml"]);
    $bestellung = array();
    foreach($bestellungXml->artikel as $artikel){
        $bestellung[] = array(
            // `  preis="${artikel.preis}" anzahl="${artikel.anzahl}" typ="${artikel.typ}" />`;
            'id' => $artikel['id'],
            'bezeichnung' => $artikel['bezeichnung'],
            'details' => $artikel['details'],
            'preis' => $artikel['preis'],
            'anzahl' => $artikel['anzahl'],
            'typ' => $artikel['typ']
        );
    }
	
    $artikeltyp = array_column($bestellung, 'typ');
    array_multisort($artikeltyp, SORT_DESC, $bestellung);



	//DB
    $bediener = $bestellungXml['bediener'];
    $tischnummer = $bestellungXml['tischnummer'];
	$datumzeit = date("Y-m-d  H:i:s");
	
	//Bestellung in DB speichern und Bestellungs-ID holen
	$sql = "INSERT INTO `bestellungen` (`tischnummer`, `zeitstempel`, `bediener_name`) "
		.  "VALUES ('$tischnummer', '$datumzeit', '$bediener')";

	executeQuery($sql);
	$bestellung_id = getInsertID();

	//Artikel der Bestellung in DB speichern
	foreach($bestellung as $artikel){
		$artId = $artikel['id'];
		$artAnzahl = $artikel['anzahl'];
		$sql = "INSERT INTO `bestellte_artikel` (`bestellung_id`, `artikel_id`, `bestellte_anzahl`) "
			 . "VALUES ('$bestellung_id', '$artId', '$artAnzahl')";
		executeQuery($sql);
    }

   include_once 'drucken.php';
	
?>
