<?php
    //EINSTELLUNGEN
        $festTitel = center("2. Öschinger Weideabtrieb");
        $printerIp = "192.168.2.172";

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
    $bediener = $bestellung['bediener'];
    $tischnummer = $bestellung['tischnummer'];
    $url = 'http://' . $printerIp . '/cgi-bin/epos/service.cgi?devid=epson&timeout=10000';
    $settings = new SimpleXMLElement(file_get_contents("settings.xml"));
    
    $content = "";
    $aktuellerTyp = "";

    foreach($bestellung->artikel as $artikel){
        $anzahl = $artikel['anzahl'];
        $bezeichnung = $artikel['bezeichnung'];
        $menge = $artikel['menge'];
        $typ = $artikel['typ'];
        $preis = $artikel['preis'] * $anzahl;

        //Die Liste ist nach Typ (Essen, Getränke, ...) sortiert (irgendwo bei artikel.php).
        //Der Typ ist int - lookup Datei: settings.xml
        if($aktuellerTyp != $typ){
            //Anderer Typ - Typ drucken
            $aktuellerTyp = $typ;
            $centered = center($artikeltypen[$typ]);
            $content += "<feed/><text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text><feed/><text smooth="true" width="1" height="1" align="left" reverse="false">$centered</text><feed/><text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>";
        }      

        //Es stehen beim Drucker exakt 42 Zeichen pro Zeile zur Verfügung 
        //Da Preis rechtsbündig müssen die Leerzeichen berechnet werden!
        //2x Bier 0,5l    6.50€
        $textlaenge= strlen($anzahl) + strlen("x ") + strlen($bezeichnung) + strlen(" ") + strlen($menge) + strlen($preis) + strlen("€");
        $leerzeichen = "";
        for($i = 0; $i < (42 - $textlaenge); $i++){
            $leerzeichen = $leerzeichen . " ";
        }

        $content += "<feed/> <text smooth=\"true\" align=\"left\" reverse=\"false\">" . $anzahl . "x " . $bezeichnung . " " . $menge . $leerzeichen . $preis . "€</text>";
    }

    $datum = center(date("%a %d.%m.%j  %H:%M") . " Uhr");
    $tischnummer = center("Tischnummer: " . $tischnummer);

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
<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>
<feed/>
<text smooth="true"  align="center" width="2" height="2" reverse="true">$tischnummer</text>
$content
</page>
<cut/>
</epos-print>
</s:Body>
</s:Envelope>
EOD;


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
