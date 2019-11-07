<!DOCTYPE html>
<html>
    <head>
        <title>Bezahlen - Bierfix</title>
        <?php include 'header.php'; ?>
        <script>
            // Bestellung - Array Aufbau:
            // ArtikelID  Bezeichnung  Menge  Preis  Anzahl
            var bestellung = new Array();
            bestellung = JSON.parse(sessionStorage.getItem("bestellung"));
            var tischnummer = sessionStorage.getItem("tischnummer");
            var bediener = localStorage.getItem("bedienername");       

            var auswahlbetrag = 0.0;
            var gesamtabrechnen = true;
            
            function addAuswahl(artikelid){
                aktuelleAuswahlAnzahl = parseInt(document.getElementById(`auswahlanzahl-${artikelid}`).innerHTML);
                gesamtanzahl = getArticleCount(artikelid);

                if(aktuelleAuswahlAnzahl < gesamtanzahl){
                    auswahlbetrag = auswahlbetrag + parseFloat(getArticlePrice(artikelid));
                    document.getElementById('auswahlbetrag').innerHTML = `Auswahlbetrag: ${auswahlbetrag.toFixed(2)}&euro;`;
                    document.getElementById(`auswahlanzahl-${artikelid}`).innerHTML = `${aktuelleAuswahlAnzahl + 1} /`;
                }
            }

            function getArticlePrice(artikelid){
                for(var i = 0; i < bestellung.length; i++){
                    if(bestellung[i][0] == artikelid){
                        return(bestellung[i][3]);
                    }
                }
            }

            function getArticleCount(artikelid){
                for(var i = 0; i < bestellung.length; i++){
                    if(bestellung[i][0] == artikelid){
                        return(parseInt(bestellung[i][4]));
                    }
                }
            }

            function berechnenBetrag(){
                //Es wurde der Betrag geändert
                var betrag = document.getElementById("betrag");
                var gegeben = document.getElementById("gegeben"); 
                var zurueck = document.getElementById("zurueck");   
                
                if(gegeben.value != "" && betrag.value != ""){
                    zurueck.value = gegeben.value - betrag.value;
                }                
            }

            function berechnenGegeben(){
                //Es wurde Gegeben geändert
                var betrag = document.getElementById("betrag");
                var gegeben = document.getElementById("gegeben"); 
                var zurueck = document.getElementById("zurueck");   
                
                if(betrag.value != "" && gegeben.value != ""){
                    zurueck.value = gegeben.value - betrag.value;
                }                
            }

            function hideRechner(){
                document.getElementById("overlay").style.display = "none";
            }

            function auswahlabrechnen(){
                gesamtabrechnen = false;

                //Rechner einblenden
                document.getElementById("overlay").style.display = "block";
                document.getElementById("betrag").value = auswahlbetrag.toFixed(2);  
                
                //Gegeben zurücksetzen
                document.getElementById("gegeben").value = "";
                document.getElementById("zurueck").value = "";
            }

            function gesamtabrechnen(){
                gesamtabrechnen = true;
            }

            window.onload = function(){
                document.getElementById('pTischnummer').innerHTML = `Tisch: ${tischnummer}`;
                document.getElementById('pBediener').innerHTML = `Bediener: ${bediener}`;

                //Erzeugung Liste
                var bezeichnung = "";
                var menge = "";
                var preis = "";
                var anzahl = "";
                var containerdiv = document.getElementById("content");
                var gesamtbetrag = 0.00;

                for(var i = 0; i < bestellung.length; i++){
                    artikelId = bestellung[i][0];
                    bezeichnung = bestellung[i][1];
                    menge = bestellung[i][2];
                    preis = (bestellung[i][3] * bestellung[i][4]).toFixed(2); //Gesamtpreis
                    gesamtbetrag = gesamtbetrag + parseFloat(preis);
                    anzahl = bestellung[i][4];
                    
                    var newdiv = document.createElement("div");
                    newdiv.className = "liste";
                    newdiv.id = `artikelid-${artikelId}`;
                    newdiv.setAttribute('onClick', `addAuswahl(${artikelId})`);

                    var auswahlanzahl = document.createElement("p");
                    auswahlanzahl.className = "listeLinks";
                    auswahlanzahl.innerHTML = `0 /`;
                    auswahlanzahl.style = "color: #FF652F; font-weight: normal;";
                    auswahlanzahl.id = `auswahlanzahl-${artikelId}`;

                    var newlinks = document.createElement("p");
                    newlinks.className = "listeLinks";
                    newlinks.innerHTML = `${anzahl}x ${bezeichnung} ${menge}`;

                    var newrechts = document.createElement("p");
                    newrechts.className = "listeRechts";
                    newrechts.innerHTML = `${preis}&euro;`;

                    newdiv.appendChild(auswahlanzahl);
                    newdiv.appendChild(newlinks);
                    newdiv.appendChild(newrechts);
                    containerdiv.appendChild(newdiv);                    
                }
                document.getElementById('gesamtbetrag').innerHTML = `Gesamtbetrag: ${gesamtbetrag}&euro;`;
            }
        </script>
    </head>
    <body>
        <div id="overlay" class="rechner">
            <p>Zu zahlen:</p>
            <input type="number" id="betrag" onkeyup="berechnenBetrag()">
            <p>Gegeben:</p>
            <input type="number" id="gegeben" onkeyup="berechnenGegeben()">
            <p>R&uuml;ckgeld:</p>
            <input type="number" id="zurueck">
            <br>
            <button type="button" onclick="hideRechner()">Zur&uuml;ck</button>
            <button type="button" onclick="abgerechnet()">Abgerechnet</button>
        </div>
        <div class="kopfzeile">
            <p class="links" id="pTischnummer"></p>
            <p class="rechts" id="pBediener"></p>
        </div>
        <div class="content" id="content">            
        </div>
        <div class="footer">
            <div class="artikelfooter">
                <p id="auswahlbetrag" style="color: #FF652F;">Auswahlbetrag: 00.00€</p>
                <hr />
                <p id="gesamtbetrag" style="color: #14A76C;">Gesamtbetrag: 00.00€</p>
                <hr />
                <a href="artikel.php"><button class="links" type="button">Zur&uuml;ck</button></a>
                <button onclick="gesamtabrechnen()" class="rechts" type="button">Gesamt abrechnen</button>
                <button onclick="auswahlabrechnen()" id="btnauswahl" class="rechts" type="button">Auswahl abrechnen</button>
            </div>
        </div>
    </body>
</html>