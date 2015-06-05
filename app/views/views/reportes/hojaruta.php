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
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Reporte Hojas de ruta <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Reportes_hoja");
        tabla.addcol("a1",  "Alumno",                       [0,1], 1, 1);
        tabla.addcol("fa", "Firma Alumno",                  [0,1], 0, 1, 0, "check");
        tabla.addcol("fpg", "Firma Profesor Guía",          [0,1], 0, 1, 0, "check");
        tabla.addcol("fra",  "Firma Revisor",               [0,1], 0, 1, 0, "check");
        tabla.addcol("fsa", "Firma Secretaría Académica",   [0,1], 0, 1, 0, "check");
        tabla.findfun();

    </script>

</div>