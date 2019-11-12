<!DOCTYPE html>
<html>
    <head>
        <title>Einstellungen - Bierfix</title>
        <?php 
		include 'header.php'; ?>
        <script>
            function saveBediener(){
                localStorage.setItem("bedienername", document.getElementById("bediener").value);
                window.open("index.php","_self");
            }

            window.onload = function() {
                var name = localStorage.getItem("bedienername");
                if(name != "null"){
                    document.getElementById("bediener").value = name;
                }
            }
        </script>
    </head>
    <body>
    <form id="formTischnr" onsubmit="saveBediener()" action="" method="post" autocomplete="off">
            <p>Bedienername</p>
            <input type="text" name="bediener" id="bediener" value=" " required>         
            <a href="index.php"><button type="button">Zur&uuml;ck</button></a>
            <button type="submit">OK</button>
        </form>
    </body>
</html>