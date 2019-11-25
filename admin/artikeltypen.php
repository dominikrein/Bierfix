<!doctype html>
<html>
<head>
	<?php include('header.html'); ?>
</head>

<body onload="getArtikeltypenFromDB(printArtikeltypen)">
	<!-- Modal -->
	<div class="modal fade" id="artikeltypenModal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="artikeltypenModalLabel">title</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body" id="artikeltypenModalContent">
			  
			  
			<div class="form-group" id="artikeltypenFormAddEdit">
				<input type="text" class="form-control" id="artikeltypenModalBezeichnung" placeholder="Bezeichnung">
				<small class="form-text text-muted">Die Bezeichnung der neuen Artikelgruppe, beispielsweise Getr&auml;nke.</small>
            </div>
            <div id="artikeltypenFormRemove">
            </div>

		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Zur&uuml;ck</button>
			<button type="button" class="btn btn-primary" id="artikeltypenModalSave">Save</button>
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
		<h1>Artikeltypen</h1>
      </nav>

	  <div class="container-fluid">
        <!--Content-->
		  <button class="btn btn-success m-2 mt-3" data-toggle="modal"  data-target="#artikeltypenModal" data-action="add"><i class="fas fa-plus-circle mr-2"></i>Hinzufügen</button>
		  <div>
			<table class="table table-striped table-hover mt-2">
				<thead>
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Bezeichnung</th>
						<th scope="col"><!--Bearbeiten--></th>
					</tr>
				</thead>
				<tbody id="artikeltypenTabelleContainer">
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