var artikelliste;

function getArtikelFromDB(){
	var req = new XMLHttpRequest();
	req.open("GET", '../php/getArtikel.php', false);
	req.send(null);
	artikelliste = JSON.parse(req.responseText);
	printArtikel();
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

function removeArtikel($id){
	alert("Remove " + $id);
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
			break;
		case "add":	document.getElementById("artikelModalLabel").innerHTML = "Artikel erstellen";
			break;
		case "remove": 	document.getElementById("artikelModalLabel").innerHTML = "Artikel entfernen";
						document.getElementById("artikelModalSave").innerHTML = "Entfernen";
						document.getElementById("artikelModalSave").className = "btn btn-danger";
						document.getElementById("artikelModalSave").setAttribute("onClick", `removeArtikel(${artikel.id})`)
						document.getElementById("artikelModalContent").innerHTML = `Soll der Artikel <strong>${artikel.bezeichnung}</strong> mit der ID <strong>${artikel.id}</strong> wirklich entfernt werden?`;
			break;
	}
})

$("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
});

getArtikelFromDB();