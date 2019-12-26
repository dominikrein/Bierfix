<!doctype html>
<html>
<head>
    <?php include('header.html'); ?>
</head>

<body onLoad="artikelstatistikOnload()">

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <?php include('menu.html'); ?>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn btn-light" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
            <h1>Artikelstatistik</h1>
        </nav>

        <div class="container-fluid">
            <div class="border px-3 pt-2 pb-3 my-3 shadow">
                <h4 class="text-muted mb-2">Verkaufte Artikel</h4>
                <div id="artikelLoading" class="loader">Loading...</div>
                <div id="chartMeistverkaufteArtikel" style="height: 250px;"></div>
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