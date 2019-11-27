<?php
    if(isset($_GET['action'])){

        include_once('database.php');    

        switch($_GET['action']){
            case 'getArtikeltypen': 
				$query = executeQuery("SELECT * FROM `artikel_typen`;");
				$artikel = [];
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$artikel[$row['id']] = $row;
				}
				echo json_encode($artikel);
				break;

            case 'getArtikel':      
				$query = executeQuery("SELECT * FROM `artikel`;");
				$artikel = [];
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$artikel[$row['id']] = $row;
				}
				echo json_encode($artikel);
				break;
				
            case 'dropDB':          
				dropDB();
				break;
            
            case 'createDB':        
				createDB();
                break;
            case 'addArtikeltyp':   
				$result = executeQuery("INSERT INTO `artikel_typen` (`id`, `bezeichnung`) VALUES (NULL, '" . $_GET['bezeichnung'] . "');");
				if($result != "1"){
					echo $result;
				}
				break;

            case 'removeArtikeltyp':   
				$result = executeQuery("DELETE FROM `artikel_typen` WHERE `id` =" . $_GET['id'] . ";");
				if($result != "1"){
					echo $result;
				}
				break;
            case 'addArtikel':         
				$bezeichnung = $_GET['bezeichnung'];
				$details = $_GET['details'];
				$typ = $_GET['typ'];
				$preis = $_GET['preis'];
				$farbe = $_GET['farbe'];

				$result = executeQuery("INSERT INTO `artikel` (`id`, `bezeichnung`, `details`, `typ`, `preis`, `farbe`) VALUES (NULL, '$bezeichnung', '$details', '$typ', '$preis', '$farbe');");
				if($result != "1"){
					echo $result;
				}
				break;
            case 'removeArtikel':     
				$id = $_GET['id'];
				$result = executeQuery("DELETE FROM `artikel` WHERE `id` =" . $id . ";");
				if($result != "1"){
					echo $result;
				}
				break;
			case 'editArtikel':
				$id = $_GET['id'];
				$bezeichnung = $_GET['bezeichnung'];
				$details = $_GET['details'];
				$typ = $_GET['typ'];
				$preis = $_GET['preis'];
				$farbe = $_GET['farbe'];
				$result = executeQuery("UPDATE `artikel` SET `bezeichnung`='$bezeichnung', `details`='$details', `typ`='$typ', `preis`='$preis', `farbe`='$farbe' WHERE `id`=" . $id . ";");
				if($result != "1"){
					echo $result;
				}
				break;
			case 'sortArtikel':
				//Das hier holt alle Artikel, sortiert nach Typ und trägt wieder in DB ein
				//Zweck: Reihenfolge der IDs passt zu Reihenfolge Typ
				$query = executeQuery("SELECT * FROM `artikel` ORDER BY typ;");
				executeQuery('DELETE FROM `artikel`;');
				executeQuery('ALTER TABLE `artikel` AUTO_INCREMENT = 1;'); //ID counter zurücksetzen
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$bezeichnung = $row['bezeichnung'];
					$details = $row['details'];
					$typ = $row['typ'];
					$preis = $row['preis'];
					$farbe = $row['farbe'];					
					executeQuery("INSERT INTO `artikel` (`id`, `bezeichnung`, `details`, `typ`, `preis`, `farbe`) VALUES (NULL, '$bezeichnung', '$details', '$typ', '$preis', '$farbe');");
				}
				break;
				
			case 'bestellungenProBedienung':
				$query = executeQuery("SELECT bestellungen.bediener_name, SUM(bestellte_artikel.bestellte_anzahl) AS summe FROM bestellungen INNER JOIN bestellte_artikel ON bestellte_artikel.bestellung_id=bestellungen.id GROUP BY bestellungen.bediener_name;");
				$output = [];
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$output[] = $row;
				}
				echo json_encode($output);
				break;
        }
    }
    else{
        http_response_code(400);
        echo "No action specified!";
    }
    
    
    
	
?>