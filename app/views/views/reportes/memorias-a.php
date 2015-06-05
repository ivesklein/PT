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
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Asignar Carreras</strong></div>
            <div class="panel-body">
                
                <form method="POST" action="#/repamemorias" enctype="multipart/form-data">
                        <input type="hidden" name="f" value="Usuarios_insertdata"></input>
                        <input type="hidden" name="var" value="carrera"></input>
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
    <div class="col-md-3">
        <div class="panel panel-default" id="agregarfinanzas">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Estado Finanzas</strong></div>
            <div class="panel-body">
                <form method="POST" action="#/repamemorias" enctype="multipart/form-data">
                        <input type="hidden" name="f" value="Usuarios_insertdata"></input>
                        <input type="hidden" name="var" value="financiero"></input>
                        <a id="down2" style="text-transform: initial;"><span class="glyphicon glyphicon-file"></span> plantilla2.csv</a>
                        <hr>
                        <input id="subir" type="file" name="csv" title="Buscar Archivo">
                        <hr>
                        <input class="btn btn-success" id="btn-agregar2" type="submit" value="Enviar"></input>
                </form>
                <div class="row mesbox" style="display:none;"><div class="col-md-12"><div class="alert alert-danger" id="mensaje" style="margin-bottom: 0;"></div></div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default" id="agregarbiblioteca">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Estado Biblioteca</strong></div>
            <div class="panel-body">
                <form method="POST" action="#/repamemorias" enctype="multipart/form-data">
                        <input type="hidden" name="f" value="Usuarios_insertdata"></input>
                        <input type="hidden" name="var" value="biblioteca"></input>
                        <a id="down3" style="text-transform: initial;"><span class="glyphicon glyphicon-file"></span> plantilla3.csv</a>
                        <hr>
                        <input id="subir" type="file" name="csv" title="Buscar Archivo">
                        <hr>
                        <input class="btn btn-success" id="btn-agregar2" type="submit" value="Enviar"></input>
                </form>
                <div class="row mesbox" style="display:none;"><div class="col-md-12"><div class="alert alert-danger" id="mensaje" style="margin-bottom: 0;"></div></div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default" id="agregaracademico">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Estado Académico</strong></div>
            <div class="panel-body">
                <form method="POST" action="#/repamemorias" enctype="multipart/form-data">
                        <input type="hidden" name="f" value="Usuarios_insertdata"></input>
                        <input type="hidden" name="var" value="academico"></input>
                        <a id="down4" style="text-transform: initial;"><span class="glyphicon glyphicon-file"></span> plantilla4.csv</a>
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
            <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Memorias Activas por Alumno <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

        </div>
    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Reportes_filtroporalumnos");
        tabla.addcol("run", "Run",          [1,1], 1, 1);
        tabla.addcol("a1","Nombre",         [1,1], 1, 1);
        tabla.addcol("mail", "Email",       [1,1], 1, 1);
        tabla.addcol("car", "Carrera",      [1,1], 1, 1);
        tabla.addcol("tema",  "Tema",       [1,1], 1, 1, 20);
        tabla.addcol("pg", "Profesor Guía", [1,1], 1, 1);
        tabla.addcol("pa1", "Promedio Notas",[1,1], 0, 1);
        tabla.addcol("em",  "Empresa",      [1,1], 1, 1);
        tabla.addcol("fin",  "Finanzas",        [1,1], 1, 1, 0, "check");
        tabla.addcol("bib",  "Biblioteca",      [1,1], 1, 1, 0, "check");
        tabla.addcol("aca",  "Estado Académico",[1,1], 1, 1, 0, "check");
        tabla.findfun();

        $("#down1").on("click",function() {
            ajx({
                data:{f:"Memorias_listaalumnos"},
                ok:function(data) {
                    if("rows" in data){
                        var csv = "Run;Mail;Carrera";
                        for(row in data.rows){
                            csv += "\n"+data.rows[row]['run']+";"+data.rows[row]['mail']+";";
                        }
                        tabla.download("plantilla1.csv",csv);
                    }
                }
            })
        })

        $("#down2").on("click",function() {
            ajx({
                data:{f:"Memorias_listaalumnos"},
                ok:function(data) {
                    if("rows" in data){
                        var csv = "Run;Mail;Estado Finanzas";
                        for(row in data.rows){
                            csv += "\n"+data.rows[row]['run']+";"+data.rows[row]['mail']+";";
                        }
                        tabla.download("plantilla2.csv",csv);
                    }
                }
            })
        })

        $("#down3").on("click",function() {
            ajx({
                data:{f:"Memorias_listaalumnos"},
                ok:function(data) {
                    if("rows" in data){
                        var csv = "Run;Mail;Estado Biblioteca";
                        for(row in data.rows){
                            csv += "\n"+data.rows[row]['run']+";"+data.rows[row]['mail']+";";
                        }
                        tabla.download("plantilla3.csv",csv);
                    }
                }
            })
        })

        $("#down4").on("click",function() {
            ajx({
                data:{f:"Memorias_listaalumnos"},
                ok:function(data) {
                    if("rows" in data){
                        var csv = "Run;Mail;Estado Académico";
                        for(row in data.rows){
                            csv += "\n"+data.rows[row]['run']+";"+data.rows[row]['mail']+";";
                        }
                        tabla.download("plantilla4.csv",csv);
                    }
                }
            })
        })        
    </script>

</div>