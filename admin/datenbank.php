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
		<div class="mt-5"><button onClick="createDB()" class="btn btn-success"><i class="fas fa-database"></i> Neue Datenbank erstellen</button></div>
		<div class="mt-3"><button onClick="dropDB()" class="btn btn-danger"><i class="fas fa-bomb"></i> Datenbank l&ouml;schen</button></div>
		
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