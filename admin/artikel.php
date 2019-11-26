<!doctype html>
<html>
<head>
	<?php include('header.html'); ?>
</head>

<body onload="getArtikelFromDB()">
	<!-- Modal -->
	<div class="modal fade" id="artikelModal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="artikelModalLabel">title</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body" id="artikelModalContent">			  
				<div id="artikelFormAddEdit">
						<div class="form-group">
							<input type="text" class="form-control" id="artikelModalBezeichnung" placeholder="Bezeichnung" required>
							<small class="form-text text-muted">Die Bezeichnung des Artikels, beispielsweise "Cola".</small>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="artikelModalDetails" placeholder="Details">
							<small class="form-text text-muted">N&auml;here Informationen zum Artikel, beispielsweise die Menge.</small>
						</div>
						<div class="form-group">
							<select class="form-control" id="artikelModalTypSelect" required>
							</select>
							<small class="form-text text-muted">Der Artikeltyp, beispielsweise Getr&auml;nke.</small>		  
						</div>
						<div>
						<div class="input-group">
							<div class="input-group-prepend">
							<div class="input-group-text">&euro;</div>
							</div>
							<input type="number" class="form-control" id="artikelModalPreis" placeholder="1,56" required>
							
						</div>
						<small class="form-text text-muted">Der Preis, ohne Euro-Zeichen.</small>
						</div>
						<div class="form-group mt-2">
							<input type="color" class="form-control" id="artikelModalColor">
							<small class="form-text text-muted">Mit dieser Farbe wird der Artikel hinterlegt.</small>
						</div>

				</div>
				<div id="artikelFormRemove">
				</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Zur&uuml;ck</button>
			<button type="button" class="btn btn-primary" id="artikelModalSave">Save</button>
		  </div>
		</div>
	  </div>
	</div>

  <div class="d-flex" id="wrapper">
	 

    <!-- Sidebar -->
   <?php include('menu.html'); ?>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-light" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
		<h1>Artikel</h1>
      </nav>

	  <div class="container-fluid">
        <!--Content-->
		  <button class="btn btn-success m-2 mt-3" data-toggle="modal"  data-target="#artikelModal" data-action="add"><i class="fas fa-plus-circle mr-2"></i>Hinzufügen</button>
		  <button class="btn btn-info m-2 mt-3" onClick="sortArtikel()"><i class="fas fa-sort-amount-down-alt mr-2"></i>Sortieren nach Typ</button>
		  <div>
			<table class="table table-striped table-hover mt-2">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Bezeichnung</th>
						<th scope="col">Details</th>
						<th scope="col">Typ</th>
						<th scope="col">Preis</th>
						<th scope="col">Farbe</th>
						<th scope="col"><!--Bearbeiten--></th>
					</tr>
				</thead>
				<tbody id="artikelTabelleContainer">
				</tbody>
			</table>
				
		  </div>
		  
		  
		  
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../js/jquery-3.4.1.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bierfix-admin.js"></script>
	


</body>
</html>