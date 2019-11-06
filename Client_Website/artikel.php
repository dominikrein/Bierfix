<?php
    if(isset($_COOKIE['bierfix-bediener'])){
        $bediener = $_COOKIE['bierfix-bediener'];   
    }
    $tischnummer = $_POST['inputTischnr'];
?>
<!DOCTYPE html>
<html>
    <script>
        var bestellung = new Array();
        function addArtikel(artikelId){
            // Bestellung - Array Aufbau:
            // ArtikelID  Bezeichnung  Menge  Preis  Anzahl
            var bezeichnung = document.getElementById(`bezeichnung${artikelId}`).innerHTML;
            var menge = document.getElementById(`menge${artikelId}`).innerHTML;
            var preis = parseFloat(document.getElementById(`preis${artikelId}`).innerHTML);
            bestellung.push([artikelId, bezeichnung, menge, preis]);
            
            
            var anzahlArtikel = document.getElementById(`anzahlArtikel${artikelId}`).innerHTML;
            if(anzahlArtikel.length == 0){
                //Falls noch keine Anzahl gesetzt
                anzahlArtikel = 0;
            }
            else{
                anzahlArtikel = parseInt(anzahlArtikel);
            }
            document.getElementById(`anzahlArtikel${artikelId}`).innerHTML = anzahlArtikel + 1;

            //Wäre vermutlich sinnvoller den Gesamtbetrag nicht jedes mal neu zu berechnen...
            var gesamtbetrag = 0.0;
            for(var i = 0; i < bestellung.length; i++){
                gesamtbetrag += bestellung[i][3];
            }
            document.getElementById('gesamtbetrag').innerHTML = `Gesamtbetrag: ${gesamtbetrag.toFixed(2)}&euro;`;
        }
    </script>
    <head>
        <title>Artikel - Bierfix</title>
        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body>
        <div class="kopfzeile">
            <p class="links">Tischnummer: <?php echo $tischnummer;?></p>
            <p class="rechts">Bediener: <?php echo $bediener;?></p>
        </div>
        <div class="inhalt">
            <?php
                // Übergangsweise werden die Artikel aus einer Textdatei geholt.
                // Eventuell würde sich hier eine DB besser eignen...
                $artikelliste = file('artikelliste.txt');
                for($i=0; $i < count($artikelliste); $i++){
                    $arr = explode(',', $artikelliste[$i]);
                    echo   '<div onclick="addArtikel(\'' . $i . '\')" id="artikel' . $i . '" class="artikel" style="background-color: ' . $arr[3] . '">
                            <div id="bezeichnung' . $i . '" class="bezeichnung">' . $arr[0] . '</div>
                            <div id="menge' . $i . '" class="menge">' . $arr[1] . '</div>
                            <div id="preis' . $i . '" class="preis">' . $arr[2] . '&euro;</div>
                            <p id="anzahlArtikel' . $i . '" class="artikelAnzahl"></p>
                            </div>';
                }
            ?>
        </div>
        <div class="artikelfooter">
            <p id="gesamtbetrag">Gesamtbetrag: 00.00€</p>
            <hr />
            <button class="links" type="button">Abbrechen</button>
            <button class="rechts" type="button">&Uuml;bersicht</button>
        </div>
        
    </body>
</html>