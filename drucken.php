<?php
    //EINSTELLUNGEN
        $festTitel = center("2. Öschinger Weideabtrieb");
        $printerIp = "192.168.2.127";

        $artikeltypen = array(
            '1'=>'Getränke',
            '2'=>'Speisen',
        );


    //ENDE EINSTELLUNGEN

    function center($text){
        //Eine ungerade Zahl nötiger Leerzeichen kann vermutlich vernachlässigt werden.
        $notwendigeLeerzeichen = 42 - strlen($text);
        $leerzeichen = "";
        for($i = 0; $i < ($notwendigeLeerzeichen / 2); $i++){
            $leerzeichen = $leerzeichen . " ";
        }
        return $leerzeichen . $text;             
    }

    /*  <?xml version='1.0' standalone='yes'?>
        <bestellung bediener="bla" tischnummer="50">
            <artikel id="0" bezeichnung="Bier" menge="0.5l" preis="3" anzahl="1" typ="1" />
            <artikel id="1" bezeichnung="Radler" menge="0.5l" preis="3" anzahl="1" typ="1" />
            <artikel id="9" bezeichnung="Pommes" menge="Schale" preis="2.5" anzahl="1" typ="2" />
            <artikel id="10" bezeichnung="Burger" menge="Stueck" preis="2.5" anzahl="1" typ="2" />
        </bestellung>
    */
         
    $bestellung = new SimpleXMLElement($_POST["xml"]);
    $bediener = center("Kassierer: " . $bestellung['bediener']);
    $tischnummer = $bestellung['tischnummer'];
    $url = 'http://' . $printerIp . '/cgi-bin/epos/service.cgi?devid=epson&timeout=10000';
    
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
            $centered = center($artikeltypen["$typ"]);
            $content .= "<feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text><feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">$centered</text><feed/><text smooth=\"true\" width=\"1\" height=\"1\" align=\"left\" reverse=\"false\">------------------------------------------</text>";
        }      

        //Es stehen beim Drucker exakt 42 Zeichen pro Zeile zur Verfügung 
        //Da Preis rechtsbündig müssen die Leerzeichen berechnet werden!
        //2x Bier 0,5l    6.50€
        $textlaenge= strlen($anzahl) + strlen("x ") + strlen($bezeichnung) + strlen(" ") + strlen($menge) + strlen($preis) + strlen("€");
        $leerzeichen = "";
        for($i = 0; $i < (42 - $textlaenge); $i++){
            $leerzeichen = $leerzeichen . " ";
        }

        $content .= "<feed/> <text smooth=\"true\" align=\"left\" reverse=\"false\">" . $anzahl . "x " . $bezeichnung . " " . $menge . $leerzeichen . $preis . "€</text>";
    }

    $datum = center(date("d.m.Y  H:i") . " Uhr");
    //$tischnummer = center("Tischnummer: " . $tischnummer);
    
    //Gesamtbetrag als String und rechtsbündig
    $gesamtbetrag = number_format($gesamtbetrag, 2, '.', '');
    $gesamtbetrag = "Gesamt: " . $gesamtbetrag . "€";
    $notwendigeLeerzeichen = 42 - strlen($gesamtbetrag);
    $leerzeichen = "";
    for($i = 0; $i < ($notwendigeLeerzeichen); $i++){
        $leerzeichen = $leerzeichen . " ";
    }
    $gesamtbetrag = $leerzeichen . $gesamtbetrag;

$request = <<<EOD
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<epos-print xmlns="http://www.epson-pos.com/schemas/2011/03/epos-print">
<page>
<feed/>
<text smooth="true" align="center" width="1" height="2">$festTitel</text>
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">$datum</text>
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">$bediener</text>
<feed/>
<text smooth="true"  align="center" width="2" height="2" reverse="true">$tischnummer</text>
$content
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">==========================================</text>
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">$gesamtbetrag</text>
</page>
<cut/>
</epos-print>
</s:Body>
</s:Envelope>
EOD;

file_put_contents("xml.xml", $request);

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

/* JavaScript POST: https://stackoverflow.com/questions/58766486/create-xmlhttprequest-from-php

        //Send print document
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'text/xml; charset=utf-8');
        xhr.setRequestHeader('If-Modified-Since', 'Thu, 01 Jan 1970 00:00:00 GMT');
        xhr.setRequestHeader('SOAPAction', '""');
        xhr.onreadystatechange = function () {

            // Receive response document
            if (xhr.readyState == 4) {

                // Parse response document
                if (xhr.status == 200) {
                    alert(xhr.responseXML.getElementsByTagName('response')[0].getAttribute('success'));
                }
                else {
                    alert('Network error occured.');
                }
            }
        };

        xhr.send(req);
*/

?>
