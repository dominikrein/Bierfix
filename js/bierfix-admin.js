var artikelliste = null;
var artikeltypenliste = null;

function getArtikelFromDB(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "../php/dbAction.php?action=getArtikel", true);
	xhr.onload = function (e) {
	if (xhr.readyState === 4) {
		if (xhr.status === 200) {
			try{
				artikelliste = JSON.parse(xhr.responseText);
			}
			catch{
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
			if(artikeltypenliste != null){
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

function addArtikel(){
	var bezeichnung = document.getElementById("artikelModalBezeichnung").value;
	var details = document.getElementById("artikelModalDetails").value;
	var sel = document.getElementById("artikelModalTypSelect");
	var selected = sel.options[sel.selectedIndex];
	var id = selected.getAttribute('data-id');
	var preis = document.getElementById("artikelModalPreis").value;
	var farbe = encodeURIComponent(document.getElementById("artikelModalColor").value);
	
	syncGet(`../php/dbAction.php?action=addArtikel&bezeichnung=${bezeichnung}&details=${details}&typ=${id}&preis=${preis}&farbe=${farbe}`);
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

$("#artikelModal").on('show.bs.modal', function(event){
	var button = $(event.relatedTarget);
	var action = button.data('action');
	var artikel = null;
	if(action != "add"){
		//Die Option gibt es bei Add nicht
		artikel = artikelliste[button.data('id')];		
	}
	switch(action){
		case "edit": document.getElementById("artikelModalLabel").innerHTML = "Artikel anpassen";
					document.getElementById("artikelModalSave").innerHTML = "&Auml;ndern";
					document.getElementById("artikelModalSave").className = "btn btn-warning";
					document.getElementById("artikelModalSave").setAttribute("onClick", "addArtikel()");
					document.getElementById("artikelFormAddEdit").style = "";
					document.getElementById("artikelFormRemove").style = "display: none;";
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
					document.getElementById("artikelModalColor").value = "#ffffff";
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

$("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
});

