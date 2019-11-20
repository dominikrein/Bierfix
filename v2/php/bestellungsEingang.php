<?php
    //EINSTELLUNGEN
    $festTitel = "2. Öschinger Weideabtrieb";
    $printerIp = "192.168.2.127";
    $statistik_dateiname = "../bierfix_statistik.csv";

    $bestellung = new SimpleXMLElement($_POST["xml"]);
    $bediener = "Kassierer: " . $bestellung['bediener'];
    $tischnummer = $bestellung['tischnummer'];
    $url = 'http://' . $printerIp . '/cgi-bin/epos/service.cgi?devid=epson&timeout=10000';

    include_once 'statistik.php';
    include_once 'drucken.php';
?>
