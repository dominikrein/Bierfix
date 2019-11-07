<!DOCTYPE html>
<html>
    <head>
        <title>Tischnummer - Bierfix</title>
        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body>        
        <form id="formTischnr" action="artikel.php" method="post" autocomplete="off">
            <p>Tischnummer</p>
            <input id="inputTischnr" name="inputTischnr" type="number" required>         
            <button type="submit">OK</button>
        </form>

        <!-- Einstellungen-Zahnrad -->
        <div class="untenrechts">
            <a href="settings.php">
                <img src="img/zahnrad.png" alt="Einstellungen" />
            </a>
        </div>
    </body>
</html>