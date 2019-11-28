<?php
    //EINSTELLUNGEN
    $festTitel = "2. Öschinger Weideabtrieb";
    $printerIp = "192.168.2.127";
    $statistik_dateiname = "../bierfix_statistik.csv";

	include_once('database.php');
	include_once 'drucken.php';
 
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

	//-------------------------------------------------------------------------------------

	$query = executeQuery("SELECT * FROM bondrucker;");
	$druckerliste = [];
	while($row = $query->fetch_array(MYSQLI_ASSOC)){
		$druckerliste[] = $row;
	}
   	
	foreach($druckerliste as $bondrucker){
		 $ipaddr[] = $bondrucker['ipaddr'];
		 $device_id = $bondrucker['device_id'];		
		 $url = "http://$ipaddr/cgi-bin/epos/service.cgi?devid=$device_id&timeout=10000";

		 //Welche Artikeltypen werden mit diesem Drucker gedruckt?
		$bondrucker_id = $bondrucker['id'];
		$query = executeQuery("SELECT artikel_typen.id, artikel_typen.bezeichnung FROM bondrucker_typen INNER JOIN artikel_typen ON bondrucker_typen.artikeltyp_id=artikel_typen.id WHERE bondrucker_typen.bondrucker_id=$bondrucker_id;");
		$typenliste = [];
		while($row = $query->fetch_array(MYSQLI_ASSOC)){
			$typenliste[] = $row;
		}
		
		$bonContent = "";
		
		foreach($typ as $typenliste){
			//Diese Typen sollen mit dem Drucker gedruckt werden
			 $typBezeichnung = $typ.bezeichnung;
			 $bonContent .= "<feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text><feed/><text align=\"center\" smooth=\"true\" width=\"1\" height=\"1\" reverse=\"false\">$typBezeichnung</text><feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text>";
			
			$query = executeQuery("SELECT artikel.bezeichnung, artikel.details, artikel.preis FROM bestellungen INNER JOIN bestellte_artikel ON bestellungen.id=bestellte_artikel.bestellung_id INNER JOIN artikel ON bestellte_artikel.artikel_id=artikel.id WHERE bestellungen.id=$bestellung_id;");
			$artikelliste = [];
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$artikelliste[] = $row;
			}
			
			//Es stehen beim Drucker exakt 42 Zeichen pro Zeile zur Verfügung 
			//Da Preis rechtsbündig müssen die Leerzeichen berechnet werden!
			//2x Bier 0,5l    6.50€
			$leerzeichen = "";                
			$zeile = "";
			while(mb_strlen($zeile, "utf-8") < 42){            
				$zeile = $anzahl . "x " . $bezeichnung . " " . $details . $leerzeichen . $preis . "€";
				$leerzeichen .= " ";
			}
			$bonContent .= "<feed/> <text smooth=\"true\" align=\"left\" reverse=\"false\">" . $zeile . "</text>";
		}
		
		
		
		
		$logo = file_get_contents("../bonlogo.txt");
		$datum = date("d.m.Y  H:i") . " Uhr";
		$tischnummer = "Tischnummer: $tischnummer";
		$gesamtbetrag = number_format($gesamtbetrag, 2, '.', '');
		
		$request = <<<EOD
			<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
			<s:Body>
			<epos-print xmlns="http://www.epson-pos.com/schemas/2011/03/epos-print">
			$logo
			<text smooth="true" align="center" width="1" height="1" reverse="false">$datum</text>
			<feed/>
			<text smooth="true" align="center" width="1" height="1" reverse="false">$bediener</text>
			<feed/>
			<feed/>
			<text smooth="true"  align="center" width="2" height="2" reverse="true"> $tischnummer </text>
			$bonContent
			<feed/>
			<text smooth="true" width="1" height="1" align="center" reverse="false">==========================================</text>
			<feed/>
			<text smooth="true" width="1" height="1" align="right" reverse="false">$gesamtbetrag</text>
			<feed/>
			<cut type="feed" />
			</epos-print>
			</s:Body>
			</s:Envelope>
EOD;
		
		
	}
	
?>
