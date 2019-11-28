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
				
			case 'meistverkaufteArtikel':
				$query = executeQuery("SELECT artikel.bezeichnung, SUM(bestellte_artikel.bestellte_anzahl) AS summe FROM bestellte_artikel INNER JOIN artikel ON artikel.id=bestellte_artikel.artikel_id GROUP BY bestellte_artikel.artikel_id ORDER BY summe ASC;");
				$output = [];
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$output[] = $row;
				}
				echo json_encode($output);
				break;
			case 'getBestellungen':
				$limit = $_GET['limit'];
				$offset = $_GET['offset'];
				
				$query = executeQuery("SELECT bestellungen.id, bestellungen.tischnummer, bestellungen.bediener_name, bestellungen.zeitstempel, bestellungen.bon FROM bestellungen LIMIT $limit OFFSET $offset;");
				$output = [];
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$output[] = $row;
				}
				echo json_encode($output);
				break;
			case 'getBestellteArtikel':
				$bestid= $_GET['bestid'];
				$query = executeQuery("SELECT bestellte_artikel.bestellung_id, bestellte_artikel.bestellte_anzahl, artikel.bezeichnung, artikel.details, artikel.preis FROM bestellte_artikel INNER JOIN artikel ON artikel.id=bestellte_artikel.artikel_id WHERE bestellte_artikel.bestellung_id=$bestid;");
				$output = [];
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$output[] = $row;
				}
				echo json_encode($output);
				break;
			
			case 'getBondrucker':			
				$query = executeQuery("SELECT * FROM bondrucker;");
				$output = [];
				while($row = $query->fetch_array(MYSQLI_ASSOC)){
					$output[] = $row;
				}
				echo json_encode($output);
				break;
			case 'addBondrucker':
					$bezeichnung = $_GET['bezeichnung'];
					$ipaddr = $_GET['ipaddr'];
					$deviceid = $_GET['deviceid'];
					$typen = $_GET['typen'];

					$result = executeQuery("INSERT INTO `bondrucker` (`id`, `bezeichnung`, `ipaddr`, `device_id`) VALUES (NULL, '$bezeichnung', '$ipaddr', '$deviceid');");
					if($result != "1"){
						echo $result;
					}
				
					$bondrucker_id = getInsertID();		
				
					foreach($typen as $typ_id){
						$result = executeQuery("INSERT INTO `bondrucker_typen` (`bondrucker_id`, `artikeltyp_id`) VALUES ('$bondrucker_id', '$typ_id');");
						if($result != "1"){
							echo $result;
						}
					}
					
				break;
			case 'getBondruckerTypen':
					$id = $_GET['id'];
					$query = executeQuery("SELECT bondrucker_typen.bondrucker_id, artikel_typen.id, artikel_typen.bezeichnung FROM bondrucker_typen INNER JOIN artikel_typen ON bondrucker_typen.artikeltyp_id=artikel_typen.id WHERE bondrucker_typen.bondrucker_id=$id;");
					$output = [];
					while($row = $query->fetch_array(MYSQLI_ASSOC)){
						$output[] = $row;
					}
					echo json_encode($output);
				break;
			case 'removeBondrucker':
					$id = $_GET['id'];
					$result = executeQuery("DELETE FROM `bondrucker_typen` WHERE `bondrucker_id`=$id;");
					if($result != "1"){
						echo $result;
					}
					$result = executeQuery("DELETE FROM `bondrucker` WHERE `id`=$id;");
					if($result != "1"){
						echo $result;
					}
				break;
			default:  http_response_code(400);
        				echo "Not found!";
				break;
        }
    }
    else{
        http_response_code(400);
        echo "No action specified!";
    }
    
    
    
	
?>