<?php
    if(isset($_COOKIE['bierfix-bediener'])){
        $bediener = $_COOKIE['bierfix-bediener'];   
    }
    if(isset($_POST['bediener'])){
        /* Formular wurde gesendet */
        $bediener = $_POST['bediener'];
        $expire = time() + (86400*30); /* Cookie 30 Tage gültig */
        setcookie("bierfix-bediener", $bediener, $expire, "/");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Einstellungen - Bierfix</title>
        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body>
    <form id="formTischnr" action="settings.php" method="post" autocomplete="off">
            <p>Bedienername</p>
            <input type="text" name="bediener" <?php if(isset($bediener)){echo ("value=\"" . $bediener . "\"");}?> required>         
            <a href="index.php"><button type="button">Zur&uuml;ck</button></a>
            <button type="submit">OK</button>
        </form>
    </body>
</html>