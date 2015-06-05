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
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Rezagados <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Reportes_rezagados");
        tabla.addcol("sem", "Semestre", [1,1], 1, 1);
        tabla.addcol("run", "Run",      [1,1], 1, 1);
        tabla.addcol("a1", "Alumno",    [1,1], 1, 1);
        tabla.addcol("pa1", "Promedio",  [1,1], 0, 1);
        tabla.addcol("tema", "Tema",    [1,1], 1, 1, 20);
        tabla.addcol("pg", "Profesor Guía",   [1,1], 1, 1);
        tabla.addcol("in", "Invitado Comisión",   [1,1], 1, 1);
        tabla.addcol("pr", "Presidente Comisión", [1,1], 1, 1);
        tabla.addcol("status", "Estado", [1,1], 1, 1);
        tabla.addcol("ver", "Ver", [0,1], 0, 1,0,"button","#/rezagado/");
        tabla.findfun();


//                f:"Reportes_rezagados",



    	//$('.table').tablesorter();
    </script>

</div>