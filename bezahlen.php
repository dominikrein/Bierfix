<!DOCTYPE html>
<html>
    <head>
        <title>Bezahlen - Bierfix</title>
        <?php include 'header.php'; ?>
        <script src="jquery.min.js" type="text/javascript"></script>
        <script>
            // Bestellung - Array Aufbau:
            // ArtikelID  Bezeichnung  Menge  Preis  Anzahl Auswahl
            //kompletteBestellung enthält die ganze Bestellung, falls nur Auswahl bezahlt wird.

            if(sessionStorage.getItem("kompletteBestellung") == "null"){
                sessionStorage.setItem("kompletteBestellung", sessionStorage.getItem("bestellung"));
            }            
            var bestellung = new Array();
            bestellung = JSON.parse(sessionStorage.getItem("bestellung"));            
            var tischnummer = sessionStorage.getItem("tischnummer");
            var bediener = localStorage.getItem("bedienername");       
            var gesamtbetrag = 0.00;
            var auswahlbetrag = 0.0;
            var gesamtabrechnen = true;

                   
            
            function addAuswahl(artikelid){
                for(var i = 0; i < bestellung.length; i++){
                    if(bestellung[i][0] == artikelid){
                        var aktuelleAuswahlAnzahl = bestellung[i][5];
                        var gesamtanzahl = bestellung[i][4];
                        
                        if(aktuelleAuswahlAnzahl < gesamtanzahl){
                            var neueAuswahlAnzahl = aktuelleAuswahlAnzahl + 1;
                            auswahlbetrag = auswahlbetrag + bestellung[i][3];
                            document.getElementById('auswahlbetrag').innerHTML = `Auswahlbetrag: ${auswahlbetrag.toFixed(2)}&euro;`;
                            document.getElementById(`auswahlanzahl-${artikelid}`).innerHTML = `${neueAuswahlAnzahl} /`;
                            bestellung[i][5] = neueAuswahlAnzahl;
                        }
                        break;
                    }
                }    
            }

            function saveBestellung(){
                var asJson = JSON.stringify(bestellung);
                sessionStorage.setItem("bestellung", asJson);
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

            function gesamtAbrechnen(){
                gesamtabrechnen = true;
                //Rechner einblenden
                document.getElementById("overlay").style.display = "block";
                document.getElementById("betrag").value = gesamtbetrag;
            }

            function abgerechnet(){
                if(gesamtabrechnen){
                    bestellungAbsenden();  
                    window.open("index.php","_self");                  
                }
                else{
                    //Es wurde nur eine Auswahl abgerechnet
                    //Folglich: Bestellung anpassen und Übersicht neu laden
                    //somit werden Anzahlen aktualisiert angezeigt
                    var loeschen = new Array();
                    for(var i = 0; i < bestellung.length; i++){
                        //Auswahl von Anzahl abziehen
                        var auswahl = bestellung[i][5];
                        var anzahl = bestellung[i][4];
                        if(auswahl < anzahl){
                            bestellung[i][4] = anzahl - auswahl;
                            bestellung[i][5] = 0; //Auswahl zurücksetzen
                            saveBestellung();
                            location.reload();
                        }
                        else{
                            //Artikel löschen da alle Anzahl bezahlt
                            loeschen.push(bestellung[i][0]); //ArtikelID zur späteren Löschung ins Array schreiben                           
                        }
                    }    
                    for(var i = 0; i < loeschen.length; i++){
                        for(var j = 0; j < bestellung.length; j++){
                            if(bestellung[j][0] == loeschen[i]){
                                bestellung.splice(j, 1);
                                break;
                            }
                        }
                    }
                    saveBestellung(); //Angepasste Bestellung speichern (original bleibt in kompletteBestellung zum Druck/Server).
                    if(bestellung.length > 0){
                        //Es gibt noch Artikel in der bestellung
                        //Seite neu laden damit angepasste Bestellung dargestellt wird.
                        location.reload();
                    }
                    else{
                        //Bestellung leer - alles bezahlt
                        bestellungAbsenden();
                        window.open("index.php","_self");
                    }
                }
               
            }

            function bestellungAbsenden(){
                //kompletteBestellung enthält komplette Bestellung (oh wunder)
                var bst = JSON.parse(sessionStorage.getItem("kompletteBestellung"));

                //create XML to transfer to php
                var xml_header = "\<?xml version='1.0' standalone='yes'?>";
                var xml_begin = `<bestellung bediener="${bediener}" tischnummer="${tischnummer}">`;
                var xml_content = "";
                
                for(var i = 0; i < bst.length; i++){
                    var menge = bst[i][2];
                    if(menge == "&nbsp;"){ menge = ""; }
                    //      0           1          2    3       4       5       6
                    // artikelId, bezeichnung, menge, preis, anzahl, auswahl, typ
                    xml_content = xml_content + `<artikel id="${bst[i][0]}" bezeichnung="${bst[i][1]}" menge="${menge}" preis="${bst[i][3]}" anzahl="${bst[i][4]}" typ="${bst[i][6]}" />`;
                }
                var xml_end = "</bestellung>";
                var xml = xml_header + xml_begin + xml_content + xml_end;
                $.ajax({
                    url: 'drucken.php',
                    type: 'POST',
                    data: {xml:xml},
                    success: function(data){
                        console.log(data);
                    }
                });
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
                

                for(var i = 0; i < bestellung.length; i++){
                    artikelId = bestellung[i][0];
                    bezeichnung = bestellung[i][1];
                    menge = bestellung[i][2];
                    preis = (bestellung[i][3] * bestellung[i][4]).toFixed(2); //Gesamtpreis
                    gesamtbetrag = (parseFloat(gesamtbetrag) + parseFloat(preis)).toFixed(2);
                    anzahl = bestellung[i][4];
                    
                    var newdiv = document.createElement("div");
                    newdiv.className = "liste";
                    newdiv.id = `artikelid-${artikelId}`;
                    newdiv.setAttribute('onClick', `addAuswahl(${artikelId})`);
                    //newdiv.setAttribute('ontouchstart', `touchstart(${artikelId})`);
                    //newdiv.setAttribute('ontouchend', `touchend(${artikelId})`);

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
            <button type="submit" onclick="abgerechnet()">Abgerechnet</button>
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
                <button onclick="gesamtAbrechnen()" class="rechts" type="button">Gesamt abrechnen</button>
                <button onclick="auswahlabrechnen()" id="btnauswahl" class="rechts" type="button">Auswahl abrechnen</button>
            </div>
        </div>
    </body>
</html>