<?php // ?>
<style type="text/css">
	.p30{
		width: 30%;
	}
	.p10{
		width: 10%;
	}
</style>
<div class="page page-table">

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Evaluaciones Docentes <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Reportes_evalguias");
        tabla.addcol("sem", "Semestre",     [1,1], 1, 1);
        tabla.addcol("pg", "Profesor Guía", [1,1], 1, 1);
        tabla.addcol("prom", "Promedio",    [1,1], 1, 1);
        tabla.findfun();

    	//$('.table').tablesorter();
    </script>

</div>