<?php
    $data = "";
    if(!file_exists($statistik_dateiname)){
        //CSV Header hinzufügen wenn Datei noch nicht existiert
        $data .= "timestamp,Datum,Bedienung,Tischnummer,Artikeltyp,ArtikelID,Bezeichnung,Detail,Anzahl,Einzelpreis";
    }


    foreach($bestellung as $artikel){
        $data .= "\r\n" . time() . ','. date("Y-m-d H:i:s.u") . ',' . $bestellungXml['bediener'] . ',' . $bestellungXml['tischnummer'] . ',';
        $data .= $artikel['typ'] . ',' . $artikel['id'] . ',' . $artikel['bezeichnung'] . ',';
        $data .= $artikel['details'] . ',' . $artikel['anzahl'] . ',' . $artikel['preis'];
    }

    file_put_contents($statistik_dateiname, $data, FILE_APPEND);
?>
