<?php
    //EINSTELLUNGEN
    $festTitel = "2. Öschinger Weideabtrieb";
    $printerIp = "192.168.2.127";
    $statistik_dateiname = "../bierfix_statistik.csv";

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
    var_dump($bestellung);

    include_once 'statistik.php';
    include_once 'drucken.php';
?>
