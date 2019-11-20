<?php
    $data = "";
    if(!file_exists($statistik_dateiname)){
        //CSV Header hinzufügen wenn Datei noch nicht existiert
        $data .= "timestamp,Datum,Bedienung,Tischnummer,Artikeltyp,ArtikelID,Bezeichnung,Detail,Anzahl,Einzelpreis";
    }


    foreach($bestellung->artikel as $artikel){
        $data .= "\r\n" . time() . ','. date("Y-m-d H:i:s.u") . ',' . $bestellung['bediener'] . ',' . $bestellung['tischnummer'] . ',';
        $data .= $artikel['typ'] . ',' . $artikel['id'] . ',' . $artikel['bezeichnung'] . ',';
        $data .= $artikel['menge'] . ',' . $artikel['anzahl'] . ',' . $artikel['preis'];
    }

    file_put_contents($statistik_dateiname, $data, FILE_APPEND);
?>
