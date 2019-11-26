<!doctype html>
<html>
<head>
  <?php include('header.html'); ?>
</head>

<body>
	<!-- Modal -->
		<div class="modal fade" id="bondruckerModal" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="bondruckerModalLabel">title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body" id="bondruckerModalContent">
				<div id="bondruckerFormAddEdit">
					<div class="alert alert-warning alert-dismissible fade show" role="alert">
					  <strong>Hinweis</strong><br>Der Bondrucker muss ePOS-f&auml;hig sein.<br>Derzeit nur mit Epson TM-T88V LAN getestet.
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="bondruckerModalBezeichnung" placeholder="Bezeichnung">
						<small class="form-text text-muted">Die Bezeichnung des Bondruckers, bspw. Getr&auml;nkedrucker.</small>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="bondruckerModalIp" placeholder="192.168.2.100">
						<small class="form-text text-muted">Die IPv4-Adresse des Bondruckers.</small>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="bondruckerModalDevid" placeholder="printer" value="printer">
						<small class="form-text text-muted">Die 'device-id' des Druckers, wird in der Drucker-Weboberfl&auml;che eingestellt.</small>
					</div>
					<div class="form-group">
						<input type="number" class="form-control" id="bondruckerModalTimeout" placeholder="10000" value="10000">
						<small class="form-text text-muted">ePOS Timeout in ms, Standard: 10000</small>
					</div>
					<div>
						<div class="border p-2">							
								<div class="form-check">
								  <input class="form-check-input" type="checkbox" id="defaultCheck2" checked>
								  <label class="form-check-label" for="defaultCheck2">
									todo
								  </label>
								</div>
						</div>
						<small class="text-muted">Diese Artikeltypen hiermit drucken</small>
					</div>
				</div>
				
				<div id="bondruckerFormRemove">
				</div>

			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Zur&uuml;ck</button>
				<button type="button" class="btn btn-primary" id="bondruckerModalSave">Save</button>
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
		<h1>Bondrucker</h1>
      </nav>

      <div class="container-fluid">
       		 <button class="btn btn-success m-2 mt-3" data-toggle="modal"  data-target="#bondruckerModal" data-action="add"><i class="fas fa-plus-circle mr-2"></i>Hinzufügen</button>
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