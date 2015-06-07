<?php // ?>
<style type="text/css">
	.p30{
		width: 30%;
	}
	.p1{
		width: 1%;
	}
    .form-inline{
        font-size: 9px;
    }
</style>
<div class="page page-table">

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Evaluaciones <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Reportes_evaluaciones");
        tabla.addcol("name","Entrega",          [0,1], 0, 1);
        tabla.addcol("fecha", "Fecha",     [0,1], 0, 1);
        tabla.addcol("n", "Evaluados",     [0,1], 0, 1);
        tabla.addcol("plazo",  "Plazo finalizado",      [0,1], 0, 1,0,"check");
        tabla.addcol("ver", "Ver",  [0,1], 0, 1, 0, "button", "#/reptarea/");
        tabla.findfun();

    </script>

</div>