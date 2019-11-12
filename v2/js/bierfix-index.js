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
    return false; //Damit form keinen reload macht
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