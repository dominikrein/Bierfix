<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bierfix.css">     
        <title>Bierfix</title>
        
    </head>
    <body class="">
        
        <div class="container sticky-top bg-dark">
            <div id="standard-header">
                <p id="standard-header-p" class="text-white text-center">Willkommen!</p>
            </div>
            <div id="neuerTisch-header">

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
                    <button type="button" onclick="btnNewTable_onClick()" class="btn btn-success my-2 py-4">Neuer Tisch</button>
                    <button type="button" class="btn btn-primary my-2 py-4">Tisch aufrufen</button>
                    <button type="button" class="btn btn-info my-2 py-4">Taschenrechner</button>
                    <button type="button" onClick="btnSettings_onClick()" class="btn btn-secondary mt-2 mb-3">Einstellungen</button>
                </ul>                
            </div>
            <!-- Ende Hauptmenü Content -->

            <!-- Neuer Tisch Content -->
            <div id="neuerTisch-content" class="d-none">
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
        </div>
        <div class="container fixed-bottom bg-dark">
            <div id="hauptmenue-footer">
                <p class="mb-1 text-white text-center"><strong>Bierfix 1.0</strong><br>Freiw. Feuerwehr Abt. &Ouml;schingen</p>
            </div>
            <div id="neuerTisch-footer">
            </div>
        </div>     

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bierfix-index.js"></script>
    </body>

</html>