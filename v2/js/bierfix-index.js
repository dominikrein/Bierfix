var gesamtbetrag = 0;
var auswahlbetrag = 0;
var touchArtikelId;
var uebersichtId;
var timer;
var touchduration = 500; 
var info = {
    "tischnummer": "",
    "bediener": ""
}
var mode_taschenrechner = false;
var mode_teilabrechnen = false;
var currentView = "hauptmenue";

function bestellungAbschliessen(){

    //create XML to transfer to php
    var xml_header = "\<?xml version='1.0' standalone='yes'?>";
    var xml_begin = `<bestellung bediener="${info.bediener}" tischnummer="${info.tischnummer}">`;
    var xml_content = "";
     for(var artikelId in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(artikelId)) continue;

        var artikel = artikelliste[artikelId];
        /*  abgerechnet: 1
            anzahl: 6
            auswahl: 0
            bezeichnung: "Radler"
            details: "0.5l"
            farbe: "#EE82EE"
            preis: "2.90"
            typ: "Getränke" 
        */
        if("anzahl" in artikel && artikel.anzahl > 0){
          //Nur übermitteln wenn auch bestellt, unbestellte Artikel nicht übermitteln
          xml_content = xml_content + `<artikel id="${artikelId}" bezeichnung="${artikel.bezeichnung}" details="${artikel.details}" preis="${artikel.preis}" anzahl="${artikel.anzahl}" typ="${artikel.typ}" />`;
        }
         }
    
    var xml_end = "</bestellung>";
    var xml = xml_header + xml_begin + xml_content + xml_end;
    $.ajax({
        url: 'php/bestellungsEingang.php',
        type: 'POST',
        data: {xml:xml},
        success: function(data){
            console.log(data);
        }
    });
    changeView("hauptmenue");
}

function uebersicht_btnGesamt_onClick(){
    bestellungAbschliessen();
}

function uebersicht_btnAuswahl_onClick(){
    mode_teilabrechnen = true;
    showRueckgeldrechner(auswahlbetrag);
}

function btnNewTable_onClick(){
    if("bedienerName" in localStorage){
        changeView("newTable");
    }
    else{
        //Bedienername muss gesetzt sein! Alert anzeigen.       
        document.getElementById("hauptmenue-alert-bediener").style.display = "block";
    }
}

function btnSettings_onClick(){
    //Settings, falls vorhanden, ausfüllen
    if("bedienerName" in localStorage){
        document.getElementById("bedienerName").value = localStorage.getItem("bedienerName");
    }
    if("spaltenAnzahl" in localStorage){
        document.getElementById("spaltenAnzahl").value = localStorage.getItem("spaltenAnzahl");
    }

    //Settings öffnen
    changeView("settings");
}

function frmSettings_onSubmit(){
    localStorage.setItem("bedienerName", document.getElementById("bedienerName").value);
    localStorage.setItem("spaltenAnzahl", document.getElementById("spaltenAnzahl").value);
    changeView("hauptmenue");
}

function settings_btnZurueck_onClick(){
    changeView("hauptmenue");
}

function artikel_btnZurueck_onClick(){
    resetArtikelSelection();
    changeView("hauptmenue");
}

function printInfoHeader(){
    info.bediener = localStorage.getItem("bedienerName");
    info.tischnummer = document.getElementById("inputTischnummer").value;

    document.getElementById("headerBedienung-p").innerHTML = `Bediener: ${info.bediener}`;
    document.getElementById("headerTisch-p").innerHTML = `Tisch: ${info.tischnummer}`;
}

function frmNewTable_onSubmit(){   
    resetArtikel();
    changeView("artikel");
    return false; //Damit form keinen reload macht
}

function resetArtikelSelection(){
    for(var artikelId in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(artikelId)) continue;
        var artikel = artikelliste[artikelId];
        if("anzahl" in artikel && artikel.anzahl > 0){
            artikel.anzahl = 0;
        }

    }
}

function uebersicht_btnZurueck_onClick(){
    changeView("artikel");
}

function artikel_btnUebersicht_onClick(){
    changeView("uebersicht");
}

function printArtikel(){
    var artikelGridContainer = document.getElementById("artikelGridContainer");
    artikelGridContainer.innerHTML = ""; //Reset
    var counter = 0;
    var spalten = 4;
    if("spaltenAnzahl" in localStorage){
        spalten = localStorage.getItem("spaltenAnzahl");
    }
    
    var currentRow = document.createElement("div");
    //Wieviele der möglichen 12 Spalten sollen benutzt werden?
    var colcount = 12 / spalten;
    //Damit die Textgröße responsive ist wird viewport benutzt. Breite der einzelnen Spalte nehmen (berechnen hat nicht geklappt :-D):
    var artikelFontSize = `${spalten}vw`;
    gesamtbetrag = 0;

    var artikelclass = "my-1";
    currentRow.className = "row text-center";

    for(var artikelId in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(artikelId)) continue;

        var artikel = artikelliste[artikelId];
        

        if(counter > 0 && counter % spalten == 0){
            //Neue Reihe
            artikelGridContainer.appendChild(currentRow);
            currentRow = document.createElement("div");
            currentRow.className = "row text-center";                       
        }

        var newArtikel = document.createElement("div");
        newArtikel.className = `col-${colcount} px-1 border border-light rounded`;
        newArtikel.style.backgroundColor = artikel.farbe;
        newArtikel.setAttribute("onClick", `artikelOnClick(${artikelId})`);
        newArtikel.setAttribute("onTouchStart", `artikelOnTouchStart(${artikelId})`);
        newArtikel.setAttribute("onTouchEnd", "artikelOnTouchEnd()");
        var korrigierteAnzahl = artikel.anzahl - (("abgerechnet" in artikel) ? artikel.abgerechnet : 0);
        var bezeichnung = document.createElement("p");
        if("anzahl" in artikel && korrigierteAnzahl > 0){
            bezeichnung.innerHTML = `<strong>${korrigierteAnzahl}x ${artikel.bezeichnung}</strong>`;
            gesamtbetrag += (artikel.preis * korrigierteAnzahl);
        }
        else{
            bezeichnung.innerHTML = `<strong>${artikel.bezeichnung}</strong>`;
        }   
        bezeichnung.className = artikelclass;
        bezeichnung.style.fontSize = artikelFontSize;
        newArtikel.appendChild(bezeichnung); 

        var details = document.createElement("p");
        details.innerHTML = artikel.details;
        details.className = artikelclass;
        details.style.fontSize = artikelFontSize;
        newArtikel.appendChild(details); 

        var preis = document.createElement("p");
        preis.innerHTML = `${artikel.preis}&euro;`;
        preis.className = artikelclass;
        preis.style.fontSize = artikelFontSize;
        newArtikel.appendChild(preis);
        
        currentRow.appendChild(newArtikel);       
        counter++;
    }
    artikelGridContainer.appendChild(currentRow);
    document.getElementById("tr-gesamt-p").innerHTML = `Gesamt: ${gesamtbetrag.toFixed(2)}&euro;`;
}

function uebersichtLoad(){
    var uebersichtListe = document.getElementById("uebersicht-liste");
    uebersichtListe.innerHTML = ""; //Reset
    var gesamtbetrag = 0;
    auswahlbetrag = 0;

    for(var artikelId in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(artikelId)) continue;

        var artikel = artikelliste[artikelId];

        if("anzahl" in artikel && artikel.anzahl > 0){
            var korrigierteAnzahl = artikel.anzahl - (("abgerechnet" in artikel) ? artikel.abgerechnet : 0);
            if(korrigierteAnzahl == 0){
                continue;
            }

            var newArtikel = document.createElement("p");
            newArtikel.className = `uebersichtEm uebersichtSpan container my-2 py-2 font-weight-bolder text-white border border-light rounded bg-dark`;
            newArtikel.setAttribute("onClick", `uebersichtOnClick(${artikelId})`);
            newArtikel.setAttribute("onTouchStart", `uebersichtOnTouchStart(${artikelId})`);
            newArtikel.setAttribute("onTouchEnd", "uebersichtOnTouchEnd()");
            gesamtbetrag += (artikel.preis * korrigierteAnzahl);
        
            newArtikel.innerHTML = `${korrigierteAnzahl}x ${artikel.bezeichnung} <span>${artikel.details}</span>`;
            if("auswahl" in artikel && artikel.auswahl > 0){
                newArtikel.innerHTML = `<em>${artikel.auswahl}/</em>${korrigierteAnzahl}x ${artikel.bezeichnung} <span>${artikel.details}</span>`;
                auswahlbetrag += (artikel.preis * artikel.auswahl);
            }
            uebersichtListe.appendChild(newArtikel); 
        }             
    }
    document.getElementById("auswahlbetrag-p").innerHTML = `Auswahl: ${auswahlbetrag.toFixed(2)}&euro;`;
    document.getElementById("gesamtbetrag-p").innerHTML = `Gesamt: ${gesamtbetrag.toFixed(2)}&euro;`;
}

function uebersichtOnTouchStart(artikelId){
    timer = setTimeout(uebersichtLongTouch, touchduration); 
    uebersichtId = artikelId;
}
function uebersichtOnTouchEnd(){
    //stops short touches from firing the event
    if (timer)
    clearTimeout(timer);
}
function uebersichtLongTouch(){
    decreaseUebersichtCounter(uebersichtId);
}

function decreaseUebersichtCounter(id){
    if("auswahl" in artikelliste[id]){
        if(artikelliste[id].auswahl > 0){
            artikelliste[id].auswahl--;
        }
    }
        
    //Neu Laden
    uebersichtLoad();
}

function resetAuswahl(){
    for(var artikelId in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(artikelId)) continue;

        var artikel = artikelliste[artikelId];
        if("auswahl" in artikel && artikel.auswahl > 0){
            artikel.auswahl = 0;
        }
    }
}

function resetArtikel(){
    for(var artikelId in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(artikelId)) continue;

        var artikel = artikelliste[artikelId];
        if("auswahl" in artikel && artikel.auswahl > 0){
            artikel.auswahl = 0;
        }
        if("anzahl" in artikel && artikel.anzahl > 0){
            artikel.anzahl = 0;
        }
    }
}

function rueckgeldrechner_rechnen(){
    var betrag = document.getElementById("input_zuZahlen");
    var gegeben = document.getElementById("input_gegeben"); 
    var zurueck = document.getElementById("input_rueckgeld");   

    if(gegeben.value != "" && betrag.value != ""){
        zurueck.value = (gegeben.value - betrag.value).toFixed(2);
    }    
}

function btnTaschenrechner_onClick(){
    resetArtikel();
    changeView("taschenrechner");
}

function uebersichtOnClick(artikelId){
    var artikel = artikelliste[artikelId];
    if(("abgerechnet" in artikel && artikel.abgerechnet < artikel.anzahl) || "auswahl" in artikel){
        if(artikel.auswahl < artikel.anzahl){
            artikel.auswahl++;
        }
    }
    else{
        if(artikel.anzahl >= 1){
            artikel.auswahl = 1;
        }
    }
    
    //Neu Laden
    uebersichtLoad();
}



function artikelOnTouchStart(artikelId){
    timer = setTimeout(artikelLongTouch, touchduration); 
    touchArtikelId = artikelId;
}

function artikelOnTouchEnd(){
    //stops short touches from firing the event
    if (timer)
        clearTimeout(timer);
}

function artikelOnClick(artikelId){
    increaseArtikelCounter(artikelId);
}

function artikelLongTouch(){
    decreaseArtikelCounter(touchArtikelId);
}

function increaseArtikelCounter(artikelId){
    if("anzahl" in artikelliste[artikelId]){
            artikelliste[artikelId].anzahl++;
    }  
    else{
        artikelliste[artikelId].anzahl = 1;
    }
    //Refresh Anzeige
    printArtikel();
}

function decreaseArtikelCounter(artikelId){
    if("anzahl" in artikelliste[artikelId]){
        if(artikelliste[artikelId].anzahl >= 1){
            artikelliste[artikelId].anzahl--;
        }
    }   
    //Refresh Anzeige
    printArtikel();
}

function newTableLoad(){
    document.getElementById("inputTischnummer").value = "";
    document.getElementById("inputTischnummer").focus();
}

function showRueckgeldrechner(betrag){
    document.getElementById("input_zuZahlen").value = betrag;
    changeView("rueckgeldrechner");
}

function trFooterzurueck(){
    if(currentView == "taschenrechner"){
        changeView("hauptmenue");
    }
    else if(currentView == "rueckgeldrechner" && mode_taschenrechner == true){
        //Rueckgeldrechner
        changeView("taschenrechner")
    }
    else if(currentView == "rueckgeldrechner" && mode_taschenrechner == false){
        changeView("uebersicht");
    }
}

function trFooterZahlen(){
    if(currentView == "taschenrechner"){
        showRueckgeldrechner(gesamtbetrag.toFixed(2));
    }
    else if(currentView == "rueckgeldrechner" && mode_taschenrechner == true){
        //Rückgeldrechner vom Taschenrechner -> Zahlen fertig -> Hauptmenü
        //Eventuell sinnvoller wieder in den Taschenrechner gehen?
        changeView("hauptmenue");
    }
    else if(currentView == "rueckgeldrechner" && mode_taschenrechner == false){
        //Uebersicht -> Auswahl oder Gesamt Zahlen fertig

        //Generell soll hier nicht unten der Gesamtbetrag angezeigt werden
        document.getElementById("tr-gesamt-p").innerHTML = "";

        if(mode_teilabrechnen == true){
            //Eventuell sind noch nicht alle Artikel abgerechnet
            for(var artikelId in artikelliste){
                // skip loop if the property is from prototype
                if (!artikelliste.hasOwnProperty(artikelId)) continue;
        
                var artikel = artikelliste[artikelId];
                
                if("anzahl" in artikel && artikel.anzahl > 0 && "auswahl" in artikel && artikel.auswahl > 0){                                     
                    if("abgerechnet" in artikel){
                        artikel.abgerechnet += artikel.auswahl;
                    } 
                    else{
                        artikel.abgerechnet = artikel.auswahl;
                    }                    
                }             
            }

            var artikelNochAbrechnenMuss = false;
            for(var artikelId in artikelliste){
                // skip loop if the property is from prototype
                if (!artikelliste.hasOwnProperty(artikelId)) continue;
        
                var artikel = artikelliste[artikelId];
                
                if("anzahl" in artikel && artikel.anzahl > 0){
                    //Der Artikel wurde bestellt
                    if("abgerechnet" in artikel && artikel.abgerechnet > 0){
                        if(artikel.abgerechnet < artikel.anzahl){
                            artikelNochAbrechnenMuss = true;
                        }
                    }else{
                        artikelNochAbrechnenMuss = true;
                    }                        
                }
            }            
            
            if(artikelNochAbrechnenMuss){
                changeView("uebersicht");
            }
            else{
                bestellungAbschliessen();
                changeView("hauptmenue");
            }          
        }
        else{
            //Gesamtabrechnen
            bestellungAbschliessen();
            changeView("hauptmenue");
        }
    }
}

function changeView(newView){
    currentView = newView;
    switch(newView){
        case "rueckgeldrechner":document.getElementById("rueckgeldrechner").style.display = "block";
                                document.getElementById("info-header").style.display = "none";
                                document.getElementById("standard-header").style.display = "none";
                                document.getElementById("taschenrechner-header").style.display = "none";
                                document.getElementById("hauptmenue-content").style.display = "none";
                                document.getElementById("artikel-content").style.display = "none";
                                document.getElementById("neuerTisch-content").style.display = "none";
                                document.getElementById("einstellungen-content").style.display = "none";
                                document.getElementById("uebersicht-content").style.display = "none";
                                document.getElementById("uebersicht-footer").style.display = "none";
                                document.getElementById("artikel-footer").style.display = "none";
                                document.getElementById("hauptmenue-footer").style.display = "none";
                                document.getElementById("taschenrechner-footer").style.display = "block";
                                document.getElementById("input_gegeben").focus();
                                document.getElementById("input_gegeben").value = "";
                                document.getElementById("input_rueckgeld").value = "";
                                break;

        case "taschenrechner":  printArtikel();
                                printInfoHeader();
                                mode_taschenrechner = true;
                                document.getElementById("rueckgeldrechner").style.display = "none";
                                document.getElementById("info-header").style.display = "none";
                                document.getElementById("taschenrechner-header").style.display = "block";
                                document.getElementById("standard-header").style.display = "none";
                                document.getElementById("hauptmenue-content").style.display = "none";
                                document.getElementById("artikel-content").style.display = "block";
                                document.getElementById("neuerTisch-content").style.display = "none";
                                document.getElementById("einstellungen-content").style.display = "none";
                                document.getElementById("uebersicht-content").style.display = "none";
                                document.getElementById("uebersicht-footer").style.display = "none";
                                document.getElementById("artikel-footer").style.display = "none";
                                document.getElementById("hauptmenue-footer").style.display = "none";
                                document.getElementById("taschenrechner-footer").style.display = "block";
                                break;

        case "uebersicht":  resetAuswahl();
                            uebersichtLoad();                            
                            document.getElementById("rueckgeldrechner").style.display = "none";
                            document.getElementById("info-header").style.display = "block";
                            document.getElementById("standard-header").style.display = "none";
                            document.getElementById("taschenrechner-header").style.display = "none";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "block";
                            document.getElementById("uebersicht-footer").style.display = "block";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "none";
                            document.getElementById("taschenrechner-footer").style.display = "none";
                            break;
                            
        case "artikel":     printArtikel();
                            printInfoHeader();
                            mode_taschenrechner = false;
                            document.getElementById("rueckgeldrechner").style.display = "none";
                            document.getElementById("info-header").style.display = "block";
                            document.getElementById("standard-header").style.display = "none";
                            document.getElementById("taschenrechner-header").style.display = "none";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "block";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "block";
                            document.getElementById("hauptmenue-footer").style.display = "none";
                            document.getElementById("taschenrechner-footer").style.display = "none";
                            break;

        case "settings":    document.getElementById("rueckgeldrechner").style.display = "none";
                            document.getElementById("info-header").style.display = "none";
                            document.getElementById("standard-header").style.display = "block";
                            document.getElementById("taschenrechner-header").style.display = "none";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "block";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "block";
                            document.getElementById("taschenrechner-footer").style.display = "none";
                            break;

        case "newTable":    newTableLoad();
                            document.getElementById("rueckgeldrechner").style.display = "none";
                            document.getElementById("info-header").style.display = "none";
                            document.getElementById("standard-header").style.display = "block";
                            document.getElementById("taschenrechner-header").style.display = "none";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "block";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "block";
                            document.getElementById("taschenrechner-footer").style.display = "none";
                            break;

        default:
        case "hauptmenue":  document.getElementById("rueckgeldrechner").style.display = "none";
                            document.getElementById("info-header").style.display = "none";
                            document.getElementById("standard-header").style.display = "block";
                            document.getElementById("taschenrechner-header").style.display = "none";
                            document.getElementById("hauptmenue-content").style.display = "block";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "block";
                            document.getElementById("taschenrechner-footer").style.display = "none";
                            break;
    }
}