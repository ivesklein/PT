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
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Evaluciones <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Reportes_evaluacionestarea");
        tabla.addcol("tema","Tema",          [1,1], 1, 1, 20);
        tabla.addcol("pg", "Profesor Guía",     [1,1], 1, 1);
        tabla.addcol("ea1", "Evaluación Alumno 1",     [0,1], 0, 1,0,"check");
        tabla.addcol("ea2",  "Evaluación Alumno 2",      [0,1], 0, 1,0,"check");
        
        var jcall = function(id){
            tabla.setvar("idtarea",id);
            tabla.findfun();
        }

    </script>

</div>