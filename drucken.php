<?php
    $bestellung = json_decode($_POST['bestellung']);
    $fp = fopen('lidn.txt', 'w');
    fwrite($fp, $bestellung);
    fclose($fp);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        
        <script type="text/javascript">
            // URL of ePOS-Print supported TM printer
            var url = 'http://192.168.2.172/cgi-bin/epos/service.cgi?devid=epson&timeout=10000';

            function button1_Click() {
                // Create print document
                var req =
                    '<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">' +
                        '<s:Body>' +
                            '<epos-print xmlns="http://www.epson-pos.com/schemas/2011/03/epos-print">' +
                            '<page>' +
                            '<feed/>' +
                    '<text smooth="true" align="center" width="1" height="2">         2. Öschinger Weideabtrieb&#10;</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">            31.10.2020  21:50 Uhr</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>' +
                            '<feed/>' +
                        '<text smooth="true"  align="center" width="2" height="2" reverse="true">  Tischnummer:  214  </text>' +
                        '<feed/>' +                   
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">|                 Essen                  |</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>' +
                            '<feed/>' +
                            '<text smooth="true" align="left" reverse="false">2x Steakweckle                       7.00€</text>' +
                            '<feed/>' +
                            '<text smooth="true" align="left" reverse="false">2x Rote Wurst                        6.00€</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">|                Getränke                |</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">------------------------------------------</text>' +
                            '<feed/>' +
                            '<text smooth="true" align="left" reverse="false">2x Apfelschorle                      5.00€</text>' +
                            '<feed/>' +
                            '<text smooth="true" align="left" reverse="false">5x Bier 0.5l                        15.00€</text>' +
                            '<feed/>' +
                            '<text smooth="true" align="left" reverse="false">7x Pfand                            14.00€</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">==========================================</text>' +
                            '<feed/>' +
                            '<text smooth="true" width="1" height="1" align="left" reverse="false">                           Gesamt:  47.00€</text>' +
                            '</page>' +
                            '<cut/>' +
                            '</epos-print>' +
                        '</s:Body>' +
                    '</s:Envelope>';

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
