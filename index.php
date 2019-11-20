<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bierfix.css">     
        <title>Bierfix</title>
        <link rel="icon" href="img/bier-50.png" type="image/png">
        <script src="artikelliste.js"></script>
    </head>
    <body class="bg-light">
        
        <div class="container sticky-top bg-dark">
            <div id="standard-header" class="clearfix">
                <p id="standard-header-p" class="text-white text-center mb-0">Willkommen!</p>
            </div>
            <div id="info-header" class="clearfix" style="display:none;">
                <div class="float-left"><p id="headerBedienung-p" class="text-info text-left font-weight-bold my-1"></p></div>
                <div class="float-right"><p id="headerTisch-p" class="text-warning text-right font-weight-bold my-1"></p></div>
            </div>
            <div id="taschenrechner-header" style="display:none;">
                <div><p class="text-center text-info font-weight-bold my-1">Taschenrechner</p></div>
            </div>
            
        </div>

        
        
        <div class="container bg-light footerabstand">
            
            <!-- Hauptmenü Content -->
            <div id="hauptmenue-content" class="text-center" style="display:block;">
                <div id="hauptmenue-alert-bediener" class="alert alert-warning alert-dismissible fade show" role="alert" style="display:none;">
                    Der Bedienername muss gesetzt sein!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <h1 class="display-4">Bierfix</h1>
                <ul class="list-group">
                    <button type="button" onclick="btnNewTable_onClick()" class="btn btn-success my-2 py-4 shadow-sm">Neuer Tisch</button>
                    <button type="button" class="btn btn-primary my-2 py-4 shadow-sm">Tisch aufrufen</button>
                    <button type="button" onclick="btnTaschenrechner_onClick()" class="btn btn-info my-2 py-4 shadow-sm">Taschenrechner</button>
                    <button type="button" onClick="btnSettings_onClick()" class="btn btn-secondary mt-2 mb-3 shadow-sm">Einstellungen</button>
                </ul>                
            </div>
            <!-- Ende Hauptmenü Content -->

            <!-- Neuer Tisch Content -->
            <div id="neuerTisch-content" class="text-center" style="display:none;">
                <h1 class="display-4 text-center">Neuer Tisch</h1>
                <form action="" onsubmit="return frmNewTable_onSubmit()">
                    <div class="form-group">
                        <label for="bedienerName">Tischnummer</label>
                        <input type="number" class="form-control" autocomplete="off" id="inputTischnummer" aria-describedby="inputTischnummerHelp" autofocus required>
                        <small id="inputTischnummerHelp" class="form-text text-muted">Nummer des aufzunehmenden Tisches</small>
                    </div>  
                    <button type="button" onclick="settings_btnZurueck_onClick()" class="btn btn-secondary">Zur&uuml;ck</button>                  
                    <button type="submit" class="btn btn-primary">Best&auml;tigen</button>
                </form>
            </div>
            <!-- Ende Neuer Tisch Content -->

            <!-- Einstellungen Content -->
            <div id="einstellungen-content" class="text-center" style="display:none;">
                <h1 class="display-4 text-center">Einstellungen</h1>
                <form onsubmit="frmSettings_onSubmit()">
                    <div class="form-group">
                        <label for="bedienerName">Bedienername</label>
                        <input type="text" autocomplete="off" class="form-control" id="bedienerName"  placeholder="Dein Name" required>
                        <small id="bedienerNameHelp" class="form-text text-muted">Dieser Name erscheint auf dem Bon.</small>
                    </div>  
                    <div class="form-group">
                        <label for="spaltenAnzahl">Artikel-Spalten</label>
                        <select class="form-control" id="spaltenAnzahl">
                            <option>3</option>
                            <option selected>4</option>
                        </select>
                        <small id="spaltenAnzahlHelp" class="form-text text-muted">Anzahl der Artikel-Spalten.</small>
                    </div> 
                    <button type="button" onclick="settings_btnZurueck_onClick()" class="btn btn-secondary">Zur&uuml;ck</button>                  
                    <button type="submit" class="btn btn-primary">Best&auml;tigen</button>
                </form>
            </div>
            <!-- Ende Einstellungen Content -->
            <!-- Artikel Content -->
            <div id="artikel-content" class="noSelect" style="display:none;">
                <div id="artikelGridContainer">                    
                </div>
            </div>
            <!-- Ende Artikel Content -->
            <!-- Übersicht Content -->
            <div id="uebersicht-content" class="noSelect" style="display:none;">
                <div id="uebersicht-liste">
                </div>
            </div>
            <!-- Ende Übersicht Content -->
            <!-- Rueckgeldrechner COntent -->
            <div id="rueckgeldrechner" class="text-center px-2 " style="display:none;">
                <div class="input-group my-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Zu Zahlen</span>
                    </div>
                    <input id="input_zuZahlen" type="number" autocomplete="off" class="form-control input-lg inputBold" onkeyup="rueckgeldrechner_rechnen()">
                </div>
                <div class="input-group my-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Gegeben</span>
                    </div>
                    <input id="input_gegeben" type="number" autocomplete="off" class="form-control input-lg inputBold" onkeyup="rueckgeldrechner_rechnen()" autofocus>
                </div>
                <div class="input-group my-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">R&uuml;ckgeld</span>
                    </div>
                    <input id="input_rueckgeld" type="number" autocomplete="off" class="form-control input-lg inputBold">
                </div>
            </div>
            <!-- Ende Rueckgeldrechner COntent -->
        </div>
        <div class="container fixed-bottom bg-dark">
            <div id="hauptmenue-footer" style="display:block;">
                <p class="mb-1 text-white text-center"><strong>Bierfix</strong><br>v1.0</p>
            </div>
            <div id="artikel-footer" style="display:none;">
                <button type="button" onclick="artikel_btnZurueck_onClick()" class="btn my-1 btn-warning">Zur&uuml;ck</button>                  
                <button type="button" onclick="artikel_btnUebersicht_onClick()" class="btn my-1 float-right btn-success">&Uuml;bersicht</button>
            </div>
            
            <div id="uebersicht-footer" class="clearfix" style="display:none;">
                <div class="clearfix" style="font-size: 1.2rem;">
                    <p id="auswahlbetrag-p" class="text-right mb-1 float-left text-info font-weight-bold"></p>
                    <p id="gesamtbetrag-p" class="text-right mb-1 float-right text-success font-weight-bold"></p>
                </div>
                <div class="clearfix">
                    <button type="button" onclick="uebersicht_btnZurueck_onClick()" class="btn my-1 btn-warning float-left">Zur&uuml;ck</button>  
                        
                    <div class="btn-group float-right ml-2">
                        <button type="button" onclick="uebersicht_btnAuswahl_onClick()" class="btn float-right my-1 btn-info">Auswahl</button>
                        <button type="button" onclick="uebersicht_btnDrucken_onClick()" class="px-2 mx-0 btn my-1 float-right btn-danger">
                            <img src="img/print-3x.png" style="filter: invert(1); height: 6vw;" >
                        </button>
                        <button type="button" onclick="uebersicht_btnGesamt_onClick()" class="btn float-right my-1 btn-success">Gesamt</button>
                    </div>
                </div>            
            </div>

            <div id="taschenrechner-footer" class="clearfix" style="display:none;">
                <div class="clearfix" style="font-size: 1.2rem;">
                    <button type="button" onclick="trFooterzurueck()" class="btn my-1 btn-warning float-left">Zur&uuml;ck</button>  
                    <p id="tr-gesamt-p" class="float-left mb-1 ml-1 text-success font-weight-bold">Gesamt: 0.00&euro;</p>
                    <button type="button" onclick="trFooterZahlen()" class="btn float-right my-1 btn-success">Zahlen</button>
                </div>
            </div>
        </div>     

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bierfix-index.js"></script>
    </body>

</html>