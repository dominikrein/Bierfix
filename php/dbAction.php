<?php
    if(isset($_GET['action'])){

        include_once('database.php');    

        switch($_GET['action']){
            case 'getArtikeltypen': $query = executeQuery("SELECT * FROM `artikel_typen`;");
                                    $artikel = [];
                                    while($row = $query->fetch_array(MYSQLI_ASSOC)){
                                        $artikel[$row['id']] = $row;
                                    }
                                    echo json_encode($artikel);
                                    break;

            case 'getArtikel':      $query = executeQuery("SELECT * FROM `artikel`;");
                                    $artikel = [];
                                    while($row = $query->fetch_array(MYSQLI_ASSOC)){
                                        $artikel[$row['id']] = $row;
                                    }
                                    echo json_encode($artikel);
                                    break;

            case 'dropDB':          dropDB();
                                    break;
            
            case 'createDB':        createDB();
                                    break;
            case 'addArtikeltyp':   $result = executeQuery("INSERT INTO `artikel_typen` (`id`, `bezeichnung`) VALUES (NULL, '" . $_GET['bezeichnung'] . "');");
                                    if($result != "1"){
                                        echo $result;
                                    }
                                    break;

            case 'removeArtikeltyp':    $result = executeQuery("DELETE FROM `artikel_typen` WHERE `id` =" . $_GET['id']);
                                        if($result != "1"){
                                            echo $result;
                                        }
                                        break;
            case 'addArtikel':          $bezeichnung = $_GET['bezeichnung'];
                                        $details = $_GET['details'];
                                        $typ = $_GET['typ'];
                                        $preis = $_GET['preis'];
                                        $farbe = $_GET['farbe'];

                                        $result = executeQuery("INSERT INTO `artikel` (`id`, `bezeichnung`, `details`, `typ`, `preis`, `farbe`) VALUES (NULL, '$bezeichnung', '$details', '$typ', '$preis', '$farbe');");
                                        if($result != "1"){
                                            echo $result;
                                        }
                                        break;
        }
    }
    else{
        http_response_code(400);
        echo "No action specified!";
    }
    
    
    
	
?>