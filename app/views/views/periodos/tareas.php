<?php //Ingreso Temas Memoria ?>
<div class="page page-table">

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th-list"></span> Configurar Tareas</strong></div>
                <div class="panel-body">
                	<form class="form-horizontal" method="POST" action="#/vista2" enctype="multipart/form-data">
                         <input type="hidden" name="f" value="temas"></input>
                        <div class="form-group">
                            <label for="" class="col-sm-3">N° de Entregas (Incluyendo defensas)</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" id="ntareas" value="0"></input>
                            </div>
                        </div>
                        <h3 class="col-sm-offset-1">Entrega 1</h3>
	                	<div class="form-group">
	                		<label for="" class="col-sm-3">Título Entrega</label>
                            <div class="col-sm-9">
                                <input class="form-control tarea" n="1" type="text" value="0"></input>
                            </div>
	                	</div>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Fecha Entrega</label>
                            <div class="col-sm-9">
                                <input class="form-control tarea" n="1" type="text" value="0"></input>
                            </div>
                        </div>
	                	<div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
	                		    <input type="submit" class="btn btn-success" value="Guardar">
                		    </div>
                        </div>
                	</form>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-save"></span> Plantilla de Ejemplo</strong></div>
                <div class="panel-body">
                	<a href="examples/temas.csv"><span class="glyphicon glyphicon-file"></span> Plantilla Ejemplo.csv</a>

                </div>
            </div>         
        </div>
    </div>

    <script type="text/javascript">
    	//$('#subir').bootstrapFileInput();
    </script>

</div>