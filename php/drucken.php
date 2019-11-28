<?php

function getHeader($logo, $bediener, $tischnummer){
	$datum = date("d.m.Y  H:i") . " Uhr";
	$tischnummer = "Tischnummer: $tischnummer";
	
	$header = <<<EOD
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
EOD;
	
	return $header;
}

function getFooter($gesamtbetrag){
	$gesamtbetrag = number_format($gesamtbetrag, 2, '.', '');
	$footer = <<<EOD
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
	return $footer;
}

function getContent(){
	foreach($bestellung as $artikel){
        $anzahl = $artikel['anzahl'];
        $bezeichnung = $artikel['bezeichnung'];
        $details = $artikel['details'];
        $typ = $artikel['typ'];
        $preis = number_format(($artikel['preis'] * $anzahl), 2, '.', '');
        $gesamtbetrag += $preis;

        //Die Liste ist nach Typ (Essen, Getränke, ...) sortiert.
        if(strcmp($typ, $aktuellerTyp) != 0){
            //Anderer Typ - Typ drucken
            $aktuellerTyp = $typ;
            $content .= "<feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text><feed/><text align=\"center\" smooth=\"true\" width=\"1\" height=\"1\" reverse=\"false\">$typ</text><feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text>";
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
        $content .= "<feed/> <text smooth=\"true\" align=\"left\" reverse=\"false\">" . $zeile . "</text>";
    }
}


?>	