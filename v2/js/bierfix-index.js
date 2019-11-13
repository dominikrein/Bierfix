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

function frmNewTable_onSubmit(){
    changeView("artikel");
    printArtikel();
    return false; //Damit form keinen reload macht
}

function printArtikel(){
    var artikelGridContainer = document.getElementById("artikelGridContainer");
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
        
        var bezeichnung = document.createElement("p");
        bezeichnung.innerHTML = `<strong>${artikel.bezeichnung}</strong>`;
        bezeichnung.className = artikelclass;
        bezeichnung.style.fontSize = artikelFontSize;
        newArtikel.appendChild(bezeichnung); 

        var details = document.createElement("p");
        details.innerHTML = artikel.details;
        details.className = artikelclass;
        details.style.fontSize = artikelFontSize;
        newArtikel.appendChild(details); 

        var preis = document.createElement("p");
        preis.innerHTML = artikel.preis;
        preis.className = artikelclass;
        preis.style.fontSize = artikelFontSize;
        newArtikel.appendChild(preis);
        
        currentRow.appendChild(newArtikel);       
        counter++;
    }
    artikelGridContainer.appendChild(currentRow);
}

function changeView(newView){
    switch(newView){
        case "artikel":     document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "block";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            break;

        case "settings":    document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "block";
                            break;

        case "newTable":    document.getElementById("hauptmenue-content").style.display = "none";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "block";
                            document.getElementById("einstellungen-content").style.display = "none";
                            break;

        default:
        case "hauptmenue":  document.getElementById("hauptmenue-content").style.display = "block";
                            document.getElementById("artikel-content").style.display = "none";
                            document.getElementById("neuerTisch-content").style.display = "none";
                            document.getElementById("einstellungen-content").style.display = "none";
                            break;
    }
}