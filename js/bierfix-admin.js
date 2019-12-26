var artikelliste = null;
var artikeltypenliste = null;
var artikelToEdit = null;

function getArtikelFromDB(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "../php/dbAction.php?action=getArtikel", true);
	xhr.onload = function (e) {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				try{
					artikelliste = JSON.parse(xhr.responseText);
				}
				catch {
					alert("Datenbankfehler " + xhr.responseText)
				}
				if(artikelliste != null){
					printArtikel();
				}
			} else {
			alert(xhr.statusText);
			}
		}
	};
	xhr.onerror = function (e) {
		alert(xhr.statusText);
	};
	xhr.send(null); 
}

function getArtikeltypenFromDB(callback){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "../php/dbAction.php?action=getArtikeltypen", true);
	xhr.onload = function (e) {
	if (xhr.readyState === 4) {
		if (xhr.status === 200) {
			try{
				artikeltypenliste = JSON.parse(xhr.responseText);
			}
			catch{
				alert("Datenbankfehler " + xhr.responseText)
			}
			if(artikeltypenliste != null && callback != null){
				callback();
			}
		} else {
		alert(xhr.statusText);
		}
	}
	};
	xhr.onerror = function (e) {
	alert(xhr.statusText);
	};
	xhr.send(null); 
}

function printArtikel(){
	var tableBody = document.getElementById("artikelTabelleContainer");
	tableBody.innerHTML = ""; //Clear content
	
	for(var id in artikelliste){
        // skip loop if the property is from prototype
        if (!artikelliste.hasOwnProperty(id)) continue;
		
		var art = artikelliste[id];
		var tr = document.createElement("tr");
		tr.innerHTML = `
			<th scope="row">${art.id}</th>
			<td>${art.bezeichnung}</td>
			<td>${art.details}</td>
			<td>${art.typ}</td>
			<td>${art.preis}&euro;</td>
			<td><p class="farbquadrat mr-2 mt-1" style="background-color: ${art.farbe};"></p>${art.farbe}</td>
			<td>
				<button class="btn btn-warning py-1 px-2" data-toggle="modal" data-target="#artikelModal" data-id="${art.id}" data-action="edit"><i class="fas fa-pencil-alt"></i></button>
				<button class="btn btn-danger py-1 px-2" data-toggle="modal" data-target="#artikelModal" data-id="${art.id}" data-action="remove"><i class="fas fa-trash-alt"></i></button>
			</td>
		`;
		tableBody.appendChild(tr);		
	}
}

function printBondrucker(bondruckerliste){
	var tableBody = document.getElementById("bondruckerTabelleContainer");
	tableBody.innerHTML = ""; //Clear content
	
	for(var id in bondruckerliste){
        // skip loop if the property is from prototype
        if (!bondruckerliste.hasOwnProperty(id)) continue;
		
		var drucker = bondruckerliste[id];
		var tr = document.createElement("tr");
		tr.innerHTML = `
			<th scope="row">${drucker.id}</th>
			<td>${drucker.bezeichnung}</td>
			<td>${drucker.ipaddr}</td>
			<td>${drucker.device_id}</td>
			<td id="typen-${drucker.id}"></td>
			<td>
				<button class="btn btn-danger py-1 px-2" data-toggle="modal" data-target="#bondruckerModal" data-id="${drucker.id}" data-action="remove"><i class="fas fa-trash-alt"></i></button>
			</td>
		`;
		tableBody.appendChild(tr);		
		asyncGetRet(`../php/dbAction.php?action=getBondruckerTypen&id=${drucker.id}`, fillBondruckerArtikeltypen);
	}
}

function fillBondruckerArtikeltypen(typen){
	let druckerId = typen[0].bondrucker_id;
	
	let zelle = document.getElementById(`typen-${druckerId}`);
	
	let content = "";
	
	for(let i=0; i<typen.length;i++){
		content += `${typen[i].id}: ${typen[i].bezeichnung}<br>`;
	}
	
	zelle.innerHTML = content;
}

function printArtikeltypen(){
	var tableBody = document.getElementById("artikeltypenTabelleContainer");
	tableBody.innerHTML = ""; //Clear content
	
	for(var id in artikeltypenliste){
        // skip loop if the property is from prototype
        if (!artikeltypenliste.hasOwnProperty(id)) continue;
		
		var typ = artikeltypenliste[id];
		var tr = document.createElement("tr");
		tr.innerHTML = `
			<th scope="row">${typ.id}</th>
			<td>${typ.bezeichnung}</td>
			<td>
				<button class="btn btn-warning py-1 px-2" data-toggle="modal" data-target="#artikeltypenModal" data-id="${typ.id}" data-action="edit"><i class="fas fa-pencil-alt"></i></button>
				<button class="btn btn-danger py-1 px-2" data-toggle="modal" data-target="#artikeltypenModal" data-id="${typ.id}" data-action="remove"><i class="fas fa-trash-alt"></i></button>
			</td>
		`;
		tableBody.appendChild(tr);		
	}
}

function removeArtikel($id){
	alert("Remove " + $id);
	location.reload();
}

function bedienerstatistikOnload(){
	//Bestellungen pro Bedienung
	asyncGetRet("../php/dbAction.php?action=bestellungenProBedienung", printGraphBedienung);
	setTimeout(bedienerstatistikOnload, 10000);
}

function artikelstatistikOnload(){
	//Meistverkaufte Artikel
	asyncGetRet("../php/dbAction.php?action=meistverkaufteArtikel", printGraphArtikel);
	setTimeout(artikelstatistikOnload, 10000);
}

function bestellungDashboardOnload(){
	//Bestellungen
	asyncGetRet("../php/dbAction.php?action=getBestellungen&limit=20&offset=0", printBestellungen);
	setTimeout(bestellungDashboardOnload, 10000);
}

function printBestellungen(bestellungen){
	var tableBody = document.getElementById("tabelleBestellungen");
	tableBody.innerHTML = ""; //Clear content
	
	for(var id in bestellungen){
        // skip loop if the property is from prototype
        if (!bestellungen.hasOwnProperty(id)) continue;
		
		var best = bestellungen[id];		
		var tr = document.createElement("tr");
		tr.innerHTML = `
			<th scope="row">${best.id}</th>
			<td>${best.bediener_name}</td>
			<td>${best.tischnummer}</td>
			<td id="artikel-${best.id}"><div class="smallLoader"></div></td>
			<td id="summe-${best.id}"><div class="smallLoader"></div></td>
			<td>${best.zeitstempel}</td>
			<td>${best.bon}</td>		
		`;
		tableBody.appendChild(tr);	
		asyncGetRet(`../php/dbAction.php?action=getBestellteArtikel&bestid=${best.id}`, fillBestellungTable);
	}
	document.getElementById("tableBestLoading").style.display="none";
}

function exportBestellungen(){

}

function removeBestellungen(){
	asyncGet("../php/dbAction.php?action=removeBestellungen", alert);
}

function fillBestellungTable(artikelset){
	// Diese FUnktion wird asynchron aufgerufen. Also erst schauen zu welcher 
	// Bestellung die Daten gehöhren anhand der mitgelieferten Bestellungsid.
	//Nehme also die ID vom ersten ARtikel
	let artikelZeile = document.getElementById(`artikel-${artikelset[0].bestellung_id}`);
	let summeZeile = document.getElementById(`summe-${artikelset[0].bestellung_id}`);
	
	let artikelText = "";
	let gesamtsumme = 0.0;
	
	for(var id in artikelset){
        // skip loop if the property is from prototype
        if (!artikelset.hasOwnProperty(id)) continue;
		
		let ba = artikelset[id];
		
		artikelText += `${ba.bestellte_anzahl}x ${ba.bezeichnung} <i>${ba.details}</i><br>`;
		gesamtsumme += ba.bestellte_anzahl * ba.preis;	
		
	}
	
	artikelZeile.innerHTML = artikelText;
	summeZeile.innerHTML = `${gesamtsumme.toFixed(2)}&euro;`;
	
}

function asyncGetRet(url, callback){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", url, true);
	xhr.onload = function (e) {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				callback(JSON.parse(xhr.responseText));	
			} else {
				alert(xhr.statusText);
			}
		}
	};
	xhr.onerror = function (e) {
		alert(xhr.statusText);
	};
	xhr.send(null); 
}

function printGraphBedienung(artikelProBedienung){
	var bestellungen = {
		animationEnabled: false,
		theme: "light2", // "light1", "light2", "dark1", "dark2"
		data: [{        
			type: "column",  
			dataPoints: [      
			]
		}]
	};
	
	for(var rowid in artikelProBedienung){ 
		let row = artikelProBedienung[rowid];
		bestellungen.data[0].dataPoints.push({ y: parseInt(row.summe), label: `${row.bediener_name}`});
	}
	
	var chart = new CanvasJS.Chart("chartBestellungenProBedienung", bestellungen);
	chart.render();
	
	//Loader ausblenden
	document.getElementById("bestLoading").style.display="none";
}

function printGraphArtikel(artikelsummary){
	var artikelsum = {
		animationEnabled: false,
		theme: "light2", // "light1", "light2", "dark1", "dark2"
		data: [{        
			type: "bar",  
			dataPoints: [      
			]
		}]
	};
	
	for(var rowid in artikelsummary){ 
		let row = artikelsummary[rowid];
		artikelsum.data[0].dataPoints.push({ y: parseInt(row.summe), label: `${row.bezeichnung}`});
	}
	
	var chart = new CanvasJS.Chart("chartMeistverkaufteArtikel", artikelsum);
	chart.render();
	
	//Loader ausblenden
	document.getElementById("artikelLoading").style.display="none";
}

function removeArtikeltyp($id){
	alert("Remove " + $id);
	location.reload();
}

function createDB(){
	asyncGet("../php/dbAction.php?action=createDB", validateDB);	
}

function dropDB(){
	asyncGet("../php/dbAction.php?action=dropDB", validateDB);
}

function validateDB(){
	//Check if DB exists or not and display to the user
	//Todo
	alert("DB OK");
}

function asyncGet(url, callback){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", url, true);
	xhr.onload = function (e) {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				if(xhr.responseText == ""){
					callback();
				}
				else{
					alert(xhr.responseText);
				}			
			} else {
				alert(xhr.statusText);
			}
		}
	};
	xhr.onerror = function (e) {
		alert(xhr.statusText);
	};
	xhr.send(null); 
}

function syncGet(url){
	let xhr = new XMLHttpRequest();

	xhr.open("GET", url, false);
	xhr.send(null);
}

function addArtikeltyp(bezeichnung){
	syncGet(`../php/dbAction.php?action=addArtikeltyp&bezeichnung=${bezeichnung}`, false);
	$("#artikeltypenModal").modal('toggle');
	getArtikeltypenFromDB(printArtikeltypen);
}

function removeArtikeltyp(id){
	syncGet(`../php/dbAction.php?action=removeArtikeltyp&id=${id}`, false);
	$("#artikeltypenModal").modal('toggle');
	getArtikeltypenFromDB(printArtikeltypen);
}

function sortArtikel(){
	asyncGet('../php/dbAction.php?action=sortArtikel', getArtikelFromDB);
}

function addArtikel(){
	var bezeichnung = document.getElementById("artikelModalBezeichnung").value;
	var details = document.getElementById("artikelModalDetails").value;
	var sel = document.getElementById("artikelModalTypSelect");
	var selected = sel.options[sel.selectedIndex];
	var typid = selected.getAttribute('data-id');
	var preis = document.getElementById("artikelModalPreis").value;
	var farbe = encodeURIComponent(document.getElementById("artikelModalColor").value);
	
	syncGet(`../php/dbAction.php?action=addArtikel&bezeichnung=${bezeichnung}&details=${details}&typ=${typid}&preis=${preis}&farbe=${farbe}`);
	getArtikelFromDB(printArtikel);
	$("#artikelModal").modal('toggle');
}

function removeArtikel(id){
	syncGet(`../php/dbAction.php?action=removeArtikel&id=${id}`);
	getArtikelFromDB(printArtikel);
	$("#artikelModal").modal('toggle');
}

function makeArtikeltypSelect(){
	var typsel = document.getElementById("artikelModalTypSelect");
	typsel.innerHTML = ""; //Remove all options
	
	for(var id in artikeltypenliste){
        // skip loop if the property is from prototype
        if (!artikeltypenliste.hasOwnProperty(id)) continue;
		
		var typ = artikeltypenliste[id];
		var opt = document.createElement("option");
		opt.innerHTML = `${typ.id} - ${typ.bezeichnung}`;
		opt.setAttribute("data-id", typ.id);
		typsel.appendChild(opt);		
	}
}

function setArtikeltypSelect(){
	makeArtikeltypSelect(); //Alle Optionen laden
	
	//Typ auswählen
	var typsel = document.getElementById("artikelModalTypSelect");
	var typ = artikeltypenliste[artikelToEdit.typ];
	typsel.value = `${typ.id} - ${typ.bezeichnung}`;		
}

function editArtikel(id){
	var bezeichnung = document.getElementById("artikelModalBezeichnung").value;
	var details = document.getElementById("artikelModalDetails").value;
	var sel = document.getElementById("artikelModalTypSelect");
	var selected = sel.options[sel.selectedIndex];
	var typid = selected.getAttribute('data-id');
	var preis = document.getElementById("artikelModalPreis").value;
	var farbe = encodeURIComponent(document.getElementById("artikelModalColor").value);
	
	syncGet(`../php/dbAction.php?action=editArtikel&id=${id}&bezeichnung=${bezeichnung}&details=${details}&typ=${typid}&preis=${preis}&farbe=${farbe}`);
	getArtikelFromDB(printArtikel);
	$("#artikelModal").modal('toggle');
}

function addBondrucker(){
	var bezeichnung = document.getElementById("bondruckerModalBezeichnung").value;
	var ipaddr = document.getElementById("bondruckerModalIp").value;
	var device_id = document.getElementById("bondruckerModalDevid").value;
	
	let url = `../php/dbAction.php?action=addBondrucker&bezeichnung=${bezeichnung}&ipaddr=${ipaddr}&deviceid=${device_id}`;
	
	//Gucken welche Checkboxen aktiviert wurden
	let checkboxen = document.getElementsByName("bontypcheck");
	for(let i=0; i<checkboxen.length; i++){
		let cb = checkboxen[i];
		if(cb.checked){			
			url += `&typen[]=${cb.dataset.id}`;
		}
	}	
	
	asyncGet(url, getAndPrintBondrucker);
	$("#bondruckerModal").modal('toggle');
}

function removeBondrucker(druckerId){
	asyncGet(`../php/dbAction.php?action=removeBondrucker&id=${druckerId}`, getAndPrintBondrucker);
	$("#bondruckerModal").modal('toggle');
}

function getAndPrintBondrucker(){
	asyncGetRet('../php/dbAction.php?action=getBondrucker', printBondrucker);
}

$("#artikelModal").on('show.bs.modal', function(event){
	var button = $(event.relatedTarget);
	var action = button.data('action');
	var artikel = null;
	if(action != "add"){
		//Die Option gibt es bei Add nicht
		artikel = artikelliste[button.data('id')];	
		artikelToEdit = artikel;
	}
	switch(action){
		case "edit":document.getElementById("artikelModalLabel").innerHTML = "Artikel anpassen";
					document.getElementById("artikelModalSave").innerHTML = "&Auml;ndern";
					document.getElementById("artikelModalSave").className = "btn btn-warning";
					document.getElementById("artikelModalSave").setAttribute("onClick", `editArtikel(${artikel.id})`);
					document.getElementById("artikelFormAddEdit").style = "";
					document.getElementById("artikelFormRemove").style = "display: none;";
					document.getElementById("artikelModalBezeichnung").value = artikel.bezeichnung;
					document.getElementById("artikelModalDetails").value = artikel.details;
					document.getElementById("artikelModalPreis").value = artikel.preis;
					document.getElementById("artikelModalColor").value = artikel.farbe;
					getArtikeltypenFromDB(setArtikeltypSelect);
			break;
		case "add":	document.getElementById("artikelModalLabel").innerHTML = "Artikel erstellen";
					document.getElementById("artikelModalSave").innerHTML = "Hinzuf&uuml;gen";
					document.getElementById("artikelModalSave").setAttribute("onClick", "addArtikel()");
					document.getElementById("artikelModalSave").className = "btn btn-success";
					document.getElementById("artikelFormAddEdit").style = "";
					document.getElementById("artikelFormRemove").style = "display: none;";
					document.getElementById("artikelModalBezeichnung").value = "";
					document.getElementById("artikelModalDetails").value = "";
					document.getElementById("artikelModalPreis").value = "";
					document.getElementById("artikelModalColor").value = "#ffff00";
					getArtikeltypenFromDB(makeArtikeltypSelect);
			break;
		case "remove": 	document.getElementById("artikelModalLabel").innerHTML = "Artikel entfernen";
						document.getElementById("artikelModalSave").innerHTML = "Entfernen";
						document.getElementById("artikelModalSave").className = "btn btn-danger";
						document.getElementById("artikelModalSave").setAttribute("onClick", `removeArtikel(${artikel.id})`)
						document.getElementById("artikelFormRemove").innerHTML = `Soll der Artikel <strong>${artikel.bezeichnung}</strong> mit der ID <strong>${artikel.id}</strong> wirklich entfernt werden?`;
						document.getElementById("artikelFormAddEdit").style = "display: none;";
						document.getElementById("artikelFormRemove").style = "";
			break;
	}
});

$("#artikeltypenModal").on('show.bs.modal', function(event){
	var button = $(event.relatedTarget);
	var action = button.data('action');
	var artikeltyp = null;
	if(action != "add"){
		//Die Option gibt es bei Add nicht
		artikeltyp = artikeltypenliste[button.data('id')];		
	}
	switch(action){
		case "edit": 	document.getElementById("artikeltypenModalLabel").innerHTML = "Artikeltyp anpassen";
						document.getElementById("artikeltypenModalBezeichnung").value = artikeltyp.bezeichnung;
						document.getElementById("artikeltypenFormAddEdit").style = "";
						document.getElementById("artikeltypenFormRemove").style = "display: none;";
						document.getElementById("artikeltypenModalSave").innerHTML = "&Auml;ndern";
						document.getElementById("artikeltypenModalSave").className = "btn btn-warning";
			break;
		case "add":		document.getElementById("artikeltypenModalLabel").innerHTML = "Artikeltyp erstellen";
						document.getElementById("artikeltypenModalSave").setAttribute("onClick", "addArtikeltyp(document.getElementById('artikeltypenModalBezeichnung').value)");
						document.getElementById("artikeltypenModalBezeichnung").value = "";
						document.getElementById("artikeltypenFormAddEdit").style = "";
						document.getElementById("artikeltypenFormRemove").style = "display: none;";
						document.getElementById("artikeltypenModalSave").innerHTML = "Hinzuf&uuml;gen";
						document.getElementById("artikeltypenModalSave").className = "btn btn-success";
						
			break;
		case "remove": 	document.getElementById("artikeltypenModalLabel").innerHTML = "Artikeltyp entfernen";
						document.getElementById("artikeltypenModalSave").innerHTML = "Entfernen";
						document.getElementById("artikeltypenModalSave").className = "btn btn-danger";
						document.getElementById("artikeltypenModalSave").setAttribute("onClick", `removeArtikeltyp(${artikeltyp.id})`);
						document.getElementById("artikeltypenFormAddEdit").style = "display: none;";
						document.getElementById("artikeltypenFormRemove").style = "";
						document.getElementById("artikeltypenFormRemove").innerHTML = `Soll der Artikeltyp <strong>${artikeltyp.bezeichnung}</strong> mit der ID <strong>${artikeltyp.id}</strong> wirklich entfernt werden?`;
			break;
	}
});

$("#bondruckerModal").on('show.bs.modal', function(event){
	let button = $(event.relatedTarget);
	var action = button.data('action');
	
	switch(action){
		case "add":		document.getElementById("bondruckerModalLabel").innerHTML = "Bondrucker hinzuf&uuml;gen";
						document.getElementById("bondruckerModalSave").setAttribute("onClick", "addBondrucker()");
						document.getElementById("bondruckerModalBezeichnung").value = "";
						document.getElementById("bondruckerFormAddEdit").style = "";
						document.getElementById("bondruckerFormRemove").style = "display: none;";
						document.getElementById("bondruckerModalSave").innerHTML = "Hinzuf&uuml;gen";
						document.getElementById("bondruckerModalSave").className = "btn btn-success";
						
						getArtikeltypenFromDB( function () {
							let checkboxen = document.getElementById("bondrucker_check"); //Checkboxen Container
							let content = "";
							for(let typid in artikeltypenliste){
								let typ = artikeltypenliste[typid];
								content += `
									<div class="form-check">
										  <input name="bontypcheck" class="form-check-input" type="checkbox" data-id="${typ.id}" id="check-${typ.id}" checked>
										  <label class="form-check-label" for="check-${typ.id}">
											${typ.bezeichnung}
										  </label>
									</div>		
								`;
							}
							checkboxen.innerHTML = content;
						});
						
			break;
		case "remove": 	document.getElementById("bondruckerModalLabel").innerHTML = "Bondrucker entfernen";
						document.getElementById("bondruckerModalSave").innerHTML = "Entfernen";
						document.getElementById("bondruckerModalSave").className = "btn btn-danger";
						document.getElementById("bondruckerModalSave").setAttribute("onClick", `removeBondrucker(${button.data('id')})`);
						document.getElementById("bondruckerFormAddEdit").style = "display: none;";
						document.getElementById("bondruckerFormRemove").style = "";
						document.getElementById("bondruckerFormRemove").innerHTML = `Soll der Bondrucker mit der ID <strong>${button.data('id')}</strong> wirklich entfernt werden?`;
			break;
	}
});

$("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
});

