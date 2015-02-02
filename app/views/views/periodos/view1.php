<?php //Ingreso Temas Memoria ?>
<div class="page page-table">

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th-list"></span> Ingresar Temas de Memoria</strong></div>
                <div class="panel-body">
                	<form class="form-horizontal" method="POST" action="#/vista2" enctype="multipart/form-data">
                         <input type="hidden" name="f" value="temas"></input>
                        <div class="form-group">
                            <label for="" class="col-sm-2">Seleccionar Periodo</label>
                            <div class="col-sm-6">
                                <span class="ui-select">
                                    <select name="periodo">
                                        <?=$drop?>
                                    </select>
                                </span>
                            </div>
                            <div class="col-sm-4">
                                <a href="#/vista3" class="btn btn-warning">Agregar Periodo</a>
                            </div>
                        </div>
	                	<div class="form-group">
	                		<label for="" class="col-sm-2">Seleccionar Archivo</label>
                            <div class="col-sm-10">
                                <input id="subir" type="file" name="csv" title="Buscar">
                            </div>
	                	</div>
	                	<div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
	                		    <input type="submit" class="btn btn-success" value="Subir">
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