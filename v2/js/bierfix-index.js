var touchArtikelId;
var uebersichtId;
var timer;
var touchduration = 500; 
var info = {
    "tischnummer": "",
    "bediener": ""
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

    //Settings öffnen
    changeView("settings");
}

function frmSettings_onSubmit(){
    localStorage.setItem("bedienerName", document.getElementById("bedienerName").value);
    changeView("hauptmenue");
}

function settings_btnZurueck_onClick(){
    changeView("hauptmenue");
}

function artikel_btnZurueck_onClick(){
    resetArtikelSelection();
    changeView("hauptmenue");
}

function frmNewTable_onSubmit(){
    info.bediener = localStorage.getItem("bedienerName");
    info.tischnummer = document.getElementById("inputTischnummer").value;

    document.getElementById("headerBedienung-p").innerHTML = `Bediener: ${info.bediener}`;
    document.getElementById("headerTisch-p").innerHTML = `Tisch: ${info.tischnummer}`;
    
    changeView("artikel");
    printArtikel();
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
    var currentRow = document.createElement("div");
    //Wieviele der möglichen 12 Spalten sollen benutzt werden?
    var colcount = 12 / spalten;
    //Damit die Textgröße responsive ist wird viewport benutzt. Breite der einzelnen Spalte nehmen (berechnen hat nicht geklappt :-D):
    var artikelFontSize = `${spalten}vw`;

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
        
        var bezeichnung = document.createElement("p");
        if("anzahl" in artikel && artikel.anzahl > 0){
            bezeichnung.innerHTML = `<strong>${artikel.anzahl}x ${artikel.bezeichnung}</strong>`;
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
}

function uebersichtLoad(){
    var uebersichtListe = document.getElementById("uebersicht-liste");
    uebersichtListe.innerHTML = ""; //Reset

    for(var artikelId in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(artikelId)) continue;

        var artikel = artikelliste[artikelId];

        if("anzahl" in artikel && artikel.anzahl > 0){
            var newArtikel = document.createElement("p");
            newArtikel.className = `container my-2 py-2 font-weight-bolder text-white border border-light rounded bg-secondary`;
            newArtikel.setAttribute("onClick", `uebersichtOnClick(${artikelId})`);
            newArtikel.setAttribute("onTouchStart", `uebersichtOnTouchStart(${artikelId})`);
            newArtikel.setAttribute("onTouchEnd", "uebersichtOnTouchEnd()");
        
            newArtikel.innerHTML = `${artikel.anzahl}x ${artikel.bezeichnung}`;
            if("auswahl" in artikel && artikel.auswahl > 0){
                newArtikel.innerHTML = `${artikel.auswahl}/${artikel.anzahl}x ${artikel.bezeichnung}`;
            }
            uebersichtListe.appendChild(newArtikel); 
        }             
    }
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

function uebersichtOnClick(artikelId){
    if("auswahl" in artikelliste[artikelId]){
        if(artikelliste[artikelId].auswahl < artikelliste[artikelId].anzahl){
            artikelliste[artikelId].auswahl++;
        }
    }
    else{
        if(artikelliste[artikelId].anzahl >= 1){
            artikelliste[artikelId].auswahl = 1;
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
}

function changeView(newView){
    switch(newView){
        case "uebersicht":  resetAuswahl();
                            uebersichtLoad();                            
                            document.getElementById("info-header").style.display = "block";
                            document.getElementById("standard-header").style.display = "none";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "block";
                            document.getElementById("uebersicht-footer").style.display = "block";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "none";
                            break;
                            
        case "artikel":     document.getElementById("info-header").style.display = "block";
                            document.getElementById("standard-header").style.display = "none";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "block";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "block";
                            document.getElementById("hauptmenue-footer").style.display = "none";
                            break;

        case "settings":    document.getElementById("info-header").style.display = "none";
                            document.getElementById("standard-header").style.display = "block";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "block";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "block";
                            break;

        case "newTable":    newTableLoad();
                            document.getElementById("info-header").style.display = "none";
                            document.getElementById("standard-header").style.display = "block";
                            document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "block";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "block";
                            break;

        default:
        case "hauptmenue":  document.getElementById("info-header").style.display = "none";
                            document.getElementById("standard-header").style.display = "block";
                            document.getElementById("hauptmenue-content").style.display = "block";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            document.getElementById("uebersicht-content").style.display = "none";
                            document.getElementById("uebersicht-footer").style.display = "none";
                            document.getElementById("artikel-footer").style.display = "none";
                            document.getElementById("hauptmenue-footer").style.display = "block";
                            break;
    }
}