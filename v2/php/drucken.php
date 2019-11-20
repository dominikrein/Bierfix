<?php
    
    $logo = file_get_contents("../bonlogo.txt");
        
    $content = "";
    $aktuellerTyp = "";

    $gesamtbetrag = 0.00;

    foreach($bestellung->artikel as $artikel){
        $anzahl = $artikel['anzahl'];
        $bezeichnung = $artikel['bezeichnung'];
        $menge = $artikel['menge'];
        $typ = $artikel['typ'];
        $preis = number_format(($artikel['preis'] * $anzahl), 2, '.', '');
        $gesamtbetrag += $preis;

        //Die Liste ist nach Typ (Essen, Getränke, ...) sortiert (irgendwo bei artikel.php).
        if(strcmp($typ, $aktuellerTyp) != 0){
            //Anderer Typ - Typ drucken
            $aktuellerTyp = $typ;
            $artikeltyp = $artikeltypen["$typ"];
            $content .= "<feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text><feed/><text align=\"center\" smooth=\"true\" width=\"1\" height=\"1\" reverse=\"false\">$artikeltyp</text><feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text>";
        }      

        //Es stehen beim Drucker exakt 42 Zeichen pro Zeile zur Verfügung 
        //Da Preis rechtsbündig müssen die Leerzeichen berechnet werden!
        //2x Bier 0,5l    6.50€
        $leerzeichen = "";                
        $zeile = "";
        while(mb_strlen($zeile, "utf-8") < 42){            
            $zeile = $anzahl . "x " . $bezeichnung . " " . $menge . $leerzeichen . $preis . "€";
            $leerzeichen .= " ";
        }
        $content .= "<feed/> <text smooth=\"true\" align=\"left\" reverse=\"false\">" . $zeile . "</text>";
    }

    $datum = date("d.m.Y  H:i") . " Uhr";
    $tischnummer = "Tischnummer: $tischnummer";
    
    //Gesamtbetrag als String und rechtsbündig
    $gesamtbetrag = number_format($gesamtbetrag, 2, '.', '');
    $gesamtbetrag = "Gesamt: " . $gesamtbetrag . "€";
    $notwendigeLeerzeichen = 42 - mb_strlen($gesamtbetrag, "utf-8");
    $leerzeichen = "";
    for($i = 0; $i < ($notwendigeLeerzeichen); $i++){
        $leerzeichen = $leerzeichen . " ";
    }
    $gesamtbetrag = $leerzeichen . $gesamtbetrag;
   

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
    $content
    <feed/>
    <text smooth="true" width="1" height="1" align="center" reverse="false">==========================================</text>
    <feed/>
    <text smooth="true" width="1" height="1" align="left" reverse="false">$gesamtbetrag</text>
    <feed/>
    <cut type="feed" />
    </epos-print>
    </s:Body>
    </s:Envelope>
    EOD;

    //Initiate cURL
    $curl = curl_init($url);
    
    //Set the Content-Type to text/xml.
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "If-Modified-Since: Thu, 01 Jan 1970 00:00:00 GMT", "SOAPAction: \"\""));
    
    //Set CURLOPT_POST to true to send a POST request.
    curl_setopt($curl, CURLOPT_POST, true);
    
    //Attach the XML string to the body of our request.
    curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
    
    //Tell cURL that we want the response to be returned as
    //a string instead of being dumped to the output.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    //Execute the POST request and send our XML.
    $result = curl_exec($curl);
    
    //Do some basic error checking.
    if(curl_errno($curl)){
        throw new Exception(curl_error($curl));
    }
    
    //Close the cURL handle.
    curl_close($curl);
    
    //Print out the response output.
    echo $result;

?>
