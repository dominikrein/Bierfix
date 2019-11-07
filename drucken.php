<?php
    
    (array)$bestellung = json_decode($_POST['postBestellung']);
    $tischnummer = $_POST['postTischnummer'];
    $bediener = $_POST['postBediener'];
    $datum = date("%a %d.%m.%j  %H:%M");
    $url = 'http://192.168.2.172/cgi-bin/epos/service.cgi?devid=epson&timeout=10000';
    $content = "";
    file_put_contents("file.txt", $_POST['postBestellung']);
foreach($bestellung as $artikel){
    $anzahl = $artikel[4];
    $beschreibung = $artikel[1] . $artikel[2];
    $preis = $artikel[3] * $anzahl;
    $content += "<feed/> <text smooth=\"true\" align=\"left\" reverse=\"false\">$anzahl x $beschreibung  $preis €</text>";
}

$request_begin = <<<EOD
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<epos-print xmlns="http://www.epson-pos.com/schemas/2011/03/epos-print">
<page>
<feed/>
<text smooth="true" align="center" width="1" height="2">         2. Öschinger Weideabtrieb&#10;</text>
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">            $datum Uhr</text>
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>
<feed/>
<text smooth="true"  align="center" width="2" height="2" reverse="true">  Tischnummer:  $tischnummer  </text>
<feed/>                   
<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">|                Artikel                 |</text>
<feed/>
<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>
$content
</page>
<cut/>
</epos-print>
</s:Body>
</s:Envelope>
EOD;


$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => ["Content-type: text/xml; charset=utf-8", "If-Modified-Since: Thu, 01 Jan 1970 00:00:00 GMT", "SOAPAction: ''"],
        'content' => $content
    )
);
# Create the context
$context = stream_context_create($opts);
# Get the response (you can use this for GET)
$result = file_get_contents($url, false, $context);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        
        <script type="text/javascript">
            // URL of ePOS-Print supported TM printer
           
            function button1_Click() {
               

                // Send print document
                var xhr = new XMLHttpRequest();
                xhr.open('POST', url, true);
                xhr.setRequestHeader('Content-Type', 'text/xml; charset=utf-8');
                xhr.setRequestHeader('If-Modified-Since', 'Thu, 01 Jan 1970 00:00:00 GMT');
                xhr.setRequestHeader('SOAPAction', '""');
                xhr.onreadystatechange = function () {

                    // Receive response document
                    if (xhr.readyState == 4) {

                        // Parse response document
                        if (xhr.status == 200) {
                            alert(xhr.responseXML.getElementsByTagName('response')[0].getAttribute('success'));
                        }
                        else {
                            alert('Network error occured.');
                        }
                    }
                };

                xhr.send(req);
            }

        </script>
    </head>

    <body>
        <div style="text-align: center;">
            <button onclick="button1_Click()">Print</button>
        </div>
    </body>
</html>
