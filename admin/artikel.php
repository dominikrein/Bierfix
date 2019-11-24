<!doctype html>
<html>
<head>
 	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/bierfix-admin.css">     
	<link rel="stylesheet" href="../css/all.css">
	<title>Admin - Bierfix</title>
	<link rel="icon" href="../img/bier-50.png" type="image/png">
</head>

<body>
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
			  
			  
			<div class="form-group">
				<input type="text" class="form-control" id="artikelModalBezeichnung" placeholder="Bezeichnung">
				<small class="form-text text-muted">Die Bezeichnung des neuen Artikels, beispielsweise "Cola".</small>
			</div>
			  <div class="form-group">
				<input type="text" class="form-control" id="artikelModalDetails" placeholder="Details">
				<small class="form-text text-muted">N&auml;here Informationen zum Artikel, beispielsweise die Menge.</small>
			</div>
			  <div class="form-group">
				<select class="form-control" id="artikelModalTypSelect">
				  <option>1</option>
				  <option>2</option>
				  <option>3</option>
				  <option>4</option>
				  <option>5</option>
				</select>
				<small class="form-text text-muted">Der Artikeltyp, beispielsweise Getr&auml;nke.</small>		  
			</div>
			  <div>
			<div class="input-group">
				<div class="input-group-prepend">
				  <div class="input-group-text">&euro;</div>
				</div>
				<input type="number" class="form-control" id="artikelModalPreis" placeholder="1,56">
				
			  </div>
			  <small class="form-text text-muted">Der Preis, ohne Euro-Zeichen.</small>
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
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><strong>Bierfix Admin</strong></div>
      <div class="list-group list-group-flush">
        <a href="index.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
        <a href="artikel.php" class="list-group-item list-group-item-action bg-light">Artikel</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Bon</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">Einstellungen</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-light" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
		<h1 class="mt-1">Artikel</h1>
      </nav>

      <div class="container-fluid">
        <!--Content-->
		  <button class="btn btn-success m-2 mt-3" data-toggle="modal"  data-target="#artikelModal" data-action="add"><i class="fas fa-plus-circle mr-2"></i>Hinzufügen</button>
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