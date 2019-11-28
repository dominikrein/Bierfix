<!doctype html>
<html>
<head>
  <?php include('header.html'); ?>
</head>

<body onLoad="dashboardOnload()">

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <?php include('menu.html'); ?>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-light" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
		<h1>Dashboard</h1>
      </nav>

      <div class="container-fluid">
		  	<div class="border px-3 pt-2 pb-3 my-3 shadow">
				<h4 class="text-muted mb-2">Verkaufte Artikel</h4>
				<div id="artikelLoading" class="loader">Loading...</div>
				<div id="chartMeistverkaufteArtikel" style="height: 250px;"></div>
		  	</div>
        	<div class="border px-3 pt-2 pb-3 my-3 shadow text-center">
				<h4 class="text-muted mb-2 text-left">Verkaufte Artikel pro Bedienung</h4>
				<div id="bestLoading" class="loader">Loading...</div>
				<div id="chartBestellungenProBedienung" style="height: 200px;"></div>
		  	</div>
		  	
		  <div class="border px-3 pt-2 pb-3 my-3 shadow">
				<h4 class="text-muted mb-4">Bestellungen</h4>
			    <div id="tableBestLoading" class="loader">Loading...</div>
				<div >
					<table class="table table-striped table-hover mt-2">
						<thead>
							<tr>
								<th scope="col">ID</th>
								<th scope="col">Bedienung</th>
								<th scope="col">Tisch</th>
								<th scope="col">Enthaltene Artikel</th>
								<th scope="col">Summe</th>
								<th scope="col">Zeitstempel</th>
								<th scope="col">Bon</th>
								<th scope="col"><!--Bearbeiten--></th>
							</tr>
						</thead>
						<tbody id="tabelleBestellungen">
						</tbody>
					</table>
			    </div>
		  	</div>
		  
		  
		  
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="../js/canvasjs.min.js"></script>
        <script src="../js/jquery-3.4.1.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bierfix-admin.js"></script>
		
	


</body>
</html>