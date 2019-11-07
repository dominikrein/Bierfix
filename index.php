<!DOCTYPE html>
<html>
    <head>
        <title>Tischnummer - Bierfix</title>
        <?php include 'header.php'; ?>
        <script>
            function saveTischnummer(){
                sessionStorage.setItem("tischnummer", document.getElementById("inputTischnr").value);
                window.location.assign("artikel.php");
                return false; //Damit Form nicht die Seite neu lädt
            }
            window.onload = function(){
                //Bestellung zurücksetzen
                sessionStorage.setItem("bestellung", null);
            }
        </script>
    </head>
    <body>        
        <form id="formTischnr" onsubmit="return saveTischnummer()" action="" method="post" autocomplete="off">
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