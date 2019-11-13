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
        <script src="artikelliste.js"></script>
    </head>
    <body class="bg-light">
        
        <div class="container sticky-top bg-dark">
            <div id="standard-header">
                <p id="standard-header-p" class="text-white text-center">Willkommen!</p>
            </div>
            <div id="info-header">
                <p id="headerBedienung-p" class="text-white text-left font-weight-bold"></p>
                <p id="headerTisch-p" class="text-white text-right font-weight-bold"></p>
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
                    <button type="button" class="btn btn-info my-2 py-4 shadow-sm">Taschenrechner</button>
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
                        <input type="number" class="form-control" id="inputTischnummer" aria-describedby="inputTischnummerHelp" required>
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
                        <input type="text" class="form-control" id="bedienerName" aria-describedby="bedienerNameHelp" placeholder="Dein Name" required>
                        <small id="bedienerNameHelp" class="form-text text-muted">Dieser Name erscheint auf dem Bon.</small>
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

            </div>
            <!-- Ende Übersicht Content -->
        </div>
        <div class="container fixed-bottom bg-dark">
            <div id="hauptmenue-footer" style="display:block;">
                <p class="mb-1 text-white text-center"><strong>Bierfix 1.0</strong><br>Freiw. Feuerwehr Abt. &Ouml;schingen</p>
            </div>
            <div id="artikel-footer" style="display:none;">
                <button type="button" onclick="artikel_btnZurueck_onClick()" class="btn my-1 btn-warning">Zur&uuml;ck</button>                  
                <button type="button" onclick="artikel_btnUebersicht_onClick()" class="btn my-1 float-right btn-success">&Uuml;bersicht</button>
            </div>
            <div id="uebersicht-footer" style="display:none;">
                    <button type="button" onclick="uebersicht_btnZurueck_onClick()" class="btn my-1 btn-warning">Zur&uuml;ck</button>  
                     
                <div class="btn-group float-right ml-2">
                    <button type="button" onclick="uebersicht_btnAuswahl_onClick()" class="btn float-right my-1 btn-info">Auswahl</button>
                    <button type="button" onclick="uebersicht_btnGesamt_onClick()" class="btn float-right my-1 btn-success">Gesamt</button>
                </div>

                <button type="button" onclick="uebersicht_btnDrucken_onClick()" class="btn my-1 float-right btn-danger">
                        <img src="img/print-3x.png" style="filter: invert(1); height: 3vw;" >
                </button>   
                                
            </div>
        </div>     

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bierfix-index.js"></script>
    </body>

</html>