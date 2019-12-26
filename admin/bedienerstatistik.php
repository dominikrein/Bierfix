<!doctype html>
<html>
<head>
    <?php include('header.html'); ?>
</head>

<body onLoad="bedienerstatistikOnload()">

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <?php include('menu.html'); ?>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn btn-light" id="menu-toggle"><span class="navbar-toggler-icon"></span></button>
            <h1>Bedienerstatistik</h1>
        </nav>

        <div class="container-fluid">
            <div class="border px-3 pt-2 pb-3 my-3 shadow text-center">
                <h4 class="text-muted mb-2 text-left">Verkaufte Artikel pro Bedienung</h4>
                <div id="bestLoading" class="loader">Loading...</div>
                <div id="chartBestellungenProBedienung" style="height: 600px;"></div>
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