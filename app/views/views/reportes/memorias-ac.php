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
    form hr{
        margin-top: 6px;
        margin-bottom: 6px;
    }
    #down1, #down2, #down3, #down4 {
        cursor:pointer;
    }
</style>
<div class="page page-table">

    <div class="col-md-3">
        <div class="panel panel-default" id="agregarcarrera">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Asignar Categoría</strong></div>
            <div class="panel-body">
                
                <form method="POST" action="#/repamemorias" enctype="multipart/form-data">
                        <input type="hidden" name="f" value="Memorias_insertdata"></input>
                        <input type="hidden" name="var" value="categoria"></input>
                        <a id="down1" style="text-transform: initial;"><span class="glyphicon glyphicon-file"></span> plantilla1.csv</a>
                        <hr>
                        <input id="subir" type="file" name="csv" title="Buscar Archivo">
                        <hr>
                        <input class="btn btn-success" id="btn-agregar2" type="submit" value="Enviar"></input>
                </form>

                <div class="row mesbox" style="display:none;"><div class="col-md-12"><div class="alert alert-danger" id="mensaje" style="margin-bottom: 0;"></div></div></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="panel panel-default tabla">
            <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Memorias Activas <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>
        </div>
    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Reportes_filtroactivo");
        <?php if(Rol::actual("SA")){ ?>
        tabla.setgm("gmt");
        <?php } ?>
        
        tabla.addcol("tema","Tema",          [1,1], 1, 1, 20);
        tabla.addcol("cat", "Categoria",     [1,1], 1, 1);
        tabla.addcol("a1",  "Alumno 1",      [1,1], 1, 1);
        tabla.addcol("pa1", "Promedio A.1",  [1,1], 0, 1);
        tabla.addcol("a2",  "Alumno 2",      [1,1], 1, 1);
        tabla.addcol("pa2", "Promedio A.2",  [1,1], 0, 1);
        tabla.addcol("pg",  "Profesor Guía", [1,1], 1, 1);
        tabla.addcol("em",  "Empresa",       [1,1], 1, 1);
        tabla.findfun();

        $("#down1").on("click",function() {
            ajx({
                data:{f:"Memorias_lista"},
                ok:function(data) {
                    if("rows" in data){
                        var csv = "id;Tema;Categoría";
                        for(row in data.rows){
                            csv += "\n"+data.rows[row]['id']+";"+data.rows[row]['tema']+";";
                        }
                        tabla.download("plantilla1.csv",csv);
                    }
                }
            })
        })

    </script>

</div>