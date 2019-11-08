<!DOCTYPE html>
<html>
    <script src="jquery.min.js" type="text/javascript"></script>
    <script>
        var bestellung = new Array();
        var tischnummer = sessionStorage.getItem("tischnummer");
        var bediener = localStorage.getItem("bedienername");

        function addArtikel(artikelId){
            // Bestellung - Array Aufbau:
            // ArtikelID  Bezeichnung  Menge  Preis  Anzahl Auswahl Typ
            var bezeichnung = document.getElementById(`bezeichnung${artikelId}`).innerHTML;
            var menge = document.getElementById(`menge${artikelId}`).innerHTML;
            var preis = parseFloat(document.getElementById(`preis${artikelId}`).innerHTML);
            var typ = parseInt(document.getElementById(`typ${artikelId}`).innerHTML);

            //Prüfen ob Element bereits vorhanden und ggf Anzahl erhöhen.
            var found = false;
            for(var i = 0; i < bestellung.length; i++){
                if(bestellung[i][0] == artikelId){
                    bestellung[i][4] = parseInt(bestellung[i][4]) + 1;
                    found = true;
                    break;
                }
            }
            if(!found){ bestellung.push([artikelId, bezeichnung, menge, preis, 1, 0, typ]); }
            
            //Artikelanzahl
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
                gesamtbetrag += (bestellung[i][3] * bestellung[i][4]); //Preis * Anzahl
            }
            document.getElementById('gesamtbetrag').innerHTML = `Gesamtbetrag: ${gesamtbetrag.toFixed(2)}&euro;`;
            sessionStorage.setItem("gesamtbetrag", gesamtbetrag.toFixed(2));
        }

        function goToBestelluebersicht(){
            //Bestellung sortieren nach Typ (1=Getränke, 2=Essen, ...)
            bestellung = bestellung.sort(function(a,b) {
                return a[6] - b[6];
            });

            var asJson = JSON.stringify(bestellung);
            sessionStorage.setItem("bestellung", asJson);
            window.open("bezahlen.php", "_self");
        }

        window.onload = function(){
            document.getElementById('pTischnummer').innerHTML = `Tisch: ${tischnummer}`;
            document.getElementById('pBediener').innerHTML = `Bediener: ${bediener}`;

            //Prüfe, ob zurück gegangen wurde - dann müssen Anzahlen und Gesamtpreis gesetzt werden
            if(sessionStorage.getItem("bestellung") != "null"){
                var restore = new Array();
                restore = JSON.parse(sessionStorage.getItem("bestellung"));
                for(var i = 0; i < restore.length; i++){
                    for(var j=0; j<restore[i][4];j++){
                        addArtikel(restore[i][0]);
                    }
                }
            
            }
        }

    </script>
    <head>
        <title>Artikel - Bierfix</title>
        <?php include 'header.php'; ?>
    </head>
    <body>
        <div class="kopfzeile">
            <p class="links" id="pTischnummer"></p>
            <p class="rechts" id="pBediener"></p>
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
                            <div id="typ' . $i . '" style="display: none;">' . $arr[4] . '</div>
                            <p id="anzahlArtikel' . $i . '" class="artikelAnzahl"></p>
                            </div>';
                }
            ?>
        </div>
        <div class="artikelfooter">
            <p id="gesamtbetrag">Gesamtbetrag: 00.00€</p>
            <hr />
            <a href="index.php"><button class="links" type="button">Abbrechen</button></a>
            <button onclick="goToBestelluebersicht()" class="rechts" type="button">&Uuml;bersicht</button>
        </div>
        
    </body>
</html>