<!doctype html>
<html>
<head>
	<?php include('header.html'); ?>
</head>

<body>
  <div class="d-flex" id="wrapper">
	 

    <!-- Sidebar -->
    <?php include('menu.html'); ?>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-light" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
		<h1>Datenbankeinstellungen</h1>
      </nav>

      <div class="container-fluid">
        <!--Content-->
		<div class="border px-3 pt-2 pb-3 my-3 shadow text-center">
			<h4 class="text-muted mb-2">Bestellungen</h4>
			<div class="mt-3"><button onClick="exportBestellungen()" class="btn btn-success w-100"><i class="fas fa-database"></i> Alle Bestellungen exportieren (csv)</button></div>
			<div class="mt-3"><button onClick="removeBestellungen()" class="btn btn-warning w-100"><i class="fas fa-database"></i> Alle Bestellungen löschen</button></div>
		</div>
		<div class="border px-3 pt-2 pb-3 my-3 shadow text-center">
			<h4 class="text-muted mb-2">Artikel und Artikeltypen</h4>
			<div class="mt-3"><button onClick="exportArtikelt()" class="btn btn-success w-100"><i class="fas fa-database"></i> Alle Artikel(-typen) exportieren</button></div>
			<div class="mt-3"><button onClick="removeArtikelt()" class="btn btn-warning w-100"><i class="fas fa-database"></i> Alle Artikel(-typen) löschen</button></div>
		</div>
		<div class="border px-3 pt-2 pb-3 my-3 shadow text-center">
			<h4 class="text-muted mb-2">Komplette Datenbank</h4>
			<div class="mt-3"><button onClick="createDB()" class="btn btn-info w-100"><i class="fas fa-database "></i> Neue Datenbank erstellen</button></div>
			<div class="mt-3"><button onClick="dropDB()" class="btn btn-danger w-100"><i class="fas fa-bomb"></i> Ganze Datenbank l&ouml;schen</button></div>
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